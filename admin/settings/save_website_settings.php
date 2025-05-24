<?php
session_start();
require_once __DIR__ . '/../alerts.php';

require_once dirname(__DIR__, 2) . '/lang/lang.php';
if (!isset($lang)) {
    setAlert("error", "⚠️ Sprachdatei fehlt.");
    header("Location: settings.php");
    exit;
}

$redirect = 'settings.php?tab=website_settings';

// 1. Daten erfassen
$siteTitle = trim($_POST['site_title'] ?? '');
$metaDescription = trim($_POST['meta_description'] ?? '');
$defaultLanguage = trim($_POST['default_language'] ?? 'de');

// Validierung
if ($siteTitle === '') {
    setAlert("error", $lang['settings_error_title_required']);
    header("Location: $redirect");
    exit;
}

// 2. Pfad zur JSON
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';

// 3. Vorherige Einstellungen laden (für Logo-Fallback oder Löschung)
$settings = [];
if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

// 4. Neue Einstellungen vorbereiten
$data = [
    'site_title' => $siteTitle,
    'meta_description' => $metaDescription,
    'default_language' => $defaultLanguage,
    'site_logo' => $settings['site_logo'] ?? null // Falls kein neues kommt, behalten wir das alte
];

// 5. Logo verarbeiten (wenn vorhanden)
if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'];
    $fileType = mime_content_type($_FILES['site_logo']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        setAlert("error", $lang['settings_error_invalid_logo']);
        header("Location: $redirect");
        exit;
    }

    // Zielpfad im assets-Ordner
    $ext = pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
    $filename = 'logo_' . time() . '.' . $ext;
    $targetDir = dirname(__DIR__, 2) . '/assets/';
    $targetPath = $targetDir . $filename;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['site_logo']['tmp_name'], $targetPath)) {
        setAlert("error", $lang['settings_error_logo_upload']);
        header("Location: $redirect");
        exit;
    }

    // Optional: altes Logo löschen (nur wenn wir ein neues speichern)
    if (!empty($settings['site_logo'])) {
        $oldPath = dirname(__DIR__, 2) . '/' . ltrim($settings['site_logo'], '/');
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }

    // Logo-URL in JSON setzen
    $data['site_logo'] = 'assets/' . $filename;
}

// 6. Einstellungen speichern
if (!is_dir(dirname($configPath))) {
    mkdir(dirname($configPath), 0755, true);
}

// Speichern der JSON ohne Escape-Zeichen für Schrägstriche
if (!file_put_contents($configPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    setAlert("error", $lang['settings_error_save_failed']);
    header("Location: $redirect");
    exit;
}

// 7. Erfolg
setAlert("success", $lang['settings_success_saved']);
header("Location: $redirect");
exit;
