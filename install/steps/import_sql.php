<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['db_connected']) || !$_SESSION['db_connected']) {
    echo json_encode(['success' => false, 'message' => 'Keine Datenbankverbindung.']);
    exit;
}

$dbConfig = $_SESSION['db'] ?? null;
if (!$dbConfig || !file_exists(__DIR__ . '/../config/db_config.json')) {
    echo json_encode(['success' => false, 'message' => 'Datenbankkonfiguration fehlt.']);
    exit;
}

$sqlFile = __DIR__ . '/install.sql';
if (!file_exists($sqlFile)) {
    echo json_encode(['success' => false, 'message' => 'install.sql nicht gefunden.']);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4",
        $dbConfig['username'],
        $dbConfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Verbindung fehlgeschlagen: ' . $e->getMessage()]);
    exit;
}

$sql = file_get_contents($sqlFile);
$sqlLines = explode("\n", $sql);
$cleanSql = '';
foreach ($sqlLines as $line) {
    $line = trim($line);
    if ($line === '' || strpos($line, '--') === 0 || strpos($line, '#') === 0) continue;
    $cleanSql .= $line . "\n";
}
$statements = [];
$buffer = '';
$inString = false;
for ($i = 0, $len = strlen($cleanSql); $i < $len; $i++) {
    $char = $cleanSql[$i];
    if ($char === "'" || $char === '"') {
        if ($inString === false) $inString = $char;
        elseif ($inString === $char) $inString = false;
    }
    if ($char === ';' && !$inString) {
        $statements[] = trim($buffer);
        $buffer = '';
    } else {
        $buffer .= $char;
    }
}
if (trim($buffer) !== '') $statements[] = trim($buffer);

$errors = [];
$success = 0;

try {
    $pdo->beginTransaction();
    foreach ($statements as $idx => $stmt) {
        if ($stmt === '') continue;
        try {
            $pdo->exec($stmt);
            $success++;
        } catch (PDOException $e) {
            $errors[] = "Fehler bei Statement #" . ($idx+1) . ": " . $e->getMessage();
            break;
        }
    }
    if (empty($errors)) {
        $pdo->commit();
        $_SESSION['sql_imported'] = true;
        echo json_encode(['success' => true, 'message' => "Alle $success SQL-Befehle wurden erfolgreich ausgeführt."]);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => implode(' | ', $errors)]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Schwerwiegender Fehler: ' . $e->getMessage()]);
} 