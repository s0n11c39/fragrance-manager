<?php
header('Content-Type: application/json');
session_start();

if (file_exists(__DIR__ . '/../config/installed.lock')) {
    echo json_encode(['success' => false, 'message' => 'Die Installation wurde bereits abgeschlossen.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$host = trim($data['host'] ?? '');
$dbname = trim($data['dbname'] ?? '');
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (!$host || !$dbname || !$username) {
    echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen.']);
    exit;
}

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $config = [
        'host' => $host,
        'dbname' => $dbname,
        'username' => $username,
        'password' => $password
    ];

    if (!is_dir(__DIR__ . '/../config')) {
        mkdir(__DIR__ . '/../config', 0755, true);
    }

    file_put_contents(__DIR__ . '/../config/db_config.json', json_encode($config, JSON_PRETTY_PRINT));
    $_SESSION['db'] = $config;
    $_SESSION['db_connected'] = true;

    // --- SQL-Import direkt nach erfolgreicher Verbindung ---
    $sqlFile = __DIR__ . '/install.sql';
    if (file_exists($sqlFile)) {
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

        foreach ($statements as $idx => $stmt) {
            if ($stmt === '') continue;
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'SQL-Import-Fehler bei Statement #' . ($idx+1) . ': ' . $e->getMessage(),
                    'statement' => $stmt,
                    'statements_executed' => $idx
                ]);
                exit;
            }
        }
        $_SESSION['sql_imported'] = true;
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'install.sql nicht gefunden.']);
        exit;
    }
    // --- ENDE SQL-Import ---

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Verbindung fehlgeschlagen: ' . htmlspecialchars($e->getMessage())]);
}
