<?php
// admin/settings/save_notification_settings.php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    setAlert("error", "Ungültiger Zugriff.");
    header("Location: $redirect");
    exit;
}


require_once "../alerts.php";

require_once dirname(__DIR__, 2) . '/lang/lang.php';
if (!isset($lang)) {
    setAlert("error", "⚠️ Sprachdatei fehlt.");
    header("Location: ../settings.php?tab=notification_settings");
    exit;
}

$redirect = 'settings.php?tab=notification_settings';
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';

// Aktuelle Einstellungen laden
$settings = [];
if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

// Neue Werte erfassen und validieren
$emailFrom = trim($_POST['email_from'] ?? '');
if (!filter_var($emailFrom, FILTER_VALIDATE_EMAIL)) {
    setAlert("error", "Bitte gib eine gültige Absenderadresse an.");
    header("Location: $redirect");
    exit;
}

// Optionalwerte erfassen (keine Pflichtvalidierung)
$settings['email_from']       = $emailFrom;
$settings['email_from_name']  = trim($_POST['email_from_name'] ?? '');
$settings['smtp_host']        = trim($_POST['smtp_host'] ?? '');
$settings['smtp_port']        = (int)($_POST['smtp_port'] ?? 587);
$settings['smtp_secure']      = in_array($_POST['smtp_secure'] ?? '', ['tls', 'ssl']) ? $_POST['smtp_secure'] : '';
$settings['smtp_user']        = trim($_POST['smtp_user'] ?? '');
$settings['smtp_pass']        = trim($_POST['smtp_pass'] ?? '');

// Speichern in JSON
if (!file_put_contents($configPath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    setAlert("error", "Fehler beim Speichern der Einstellungen.");
    header("Location: $redirect");
    exit;
}

setAlert("success", "Benachrichtigungseinstellungen wurden gespeichert.");
header("Location: $redirect");
exit;
