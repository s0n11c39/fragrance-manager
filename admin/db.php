<?php

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Funktion für Weiterleitung zu einer freundlichen Fehlerseite
function redirectToError($msg) {
    $encoded = urlencode($msg);
    header("Location: error.php?msg=$encoded");
    exit;
}

$configPath = realpath(__DIR__ . '/../config/db_config.json');

// Prüfen ob die Konfigurationsdatei existiert
if (!$configPath || !file_exists($configPath)) {
    redirectToError($lang['db_config_missing'] ?? "Fehlende Konfiguration");
}

// Konfiguration laden
$config = json_decode(file_get_contents($configPath), true);

// Prüfen ob wichtige Felder vorhanden sind
$host   = $config['host']   ?? null;
$dbname = $config['dbname'] ?? null;
$user   = $config['username']   ?? null;
$pass   = $config['password']   ?? '';

// Validierung
if (!$host || !$dbname || !$user) {
    redirectToError($lang['db_config_invalid'] ?? "Ungültige Konfiguration");
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    redirectToError($lang['db_connection_failed'] ?? "Verbindung fehlgeschlagen");
}
