<?php
header('Content-Type: application/json');
session_start();

// DB-Verbindung prüfen
$configPath = '../config/db_config.json';
if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'message' => 'Keine DB-Konfiguration gefunden.']);
    exit;
}

$db = json_decode(file_get_contents($configPath), true);
$dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB-Verbindung fehlgeschlagen: ' . $e->getMessage()]);
    exit;
}

// Nutzerdaten empfangen
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$password2 = $data['password2'] ?? '';

if (!$username || !$email || !$password || !$password2) {
    echo json_encode(['success' => false, 'message' => 'Alle Felder sind erforderlich.']);
    exit;
}

if ($password !== $password2) {
    echo json_encode(['success' => false, 'message' => 'Die Passwörter stimmen nicht überein.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Das Passwort muss mindestens 6 Zeichen lang sein.']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute([$username, $email, $hash]);

    // Lockdatei zur Markierung der Installation
    file_put_contents('../config/installed.lock', 'installed: ' . date('Y-m-d H:i:s'));
    session_destroy();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler beim Einfügen: ' . $e->getMessage()]);
}
