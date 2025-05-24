<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$title = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
$language = trim($data['language'] ?? 'de');

if (!$title || !$description || !$language) {
    echo json_encode(['success' => false, 'message' => 'Alle Felder sind erforderlich.']);
    exit;
}

$settings = [
    'title' => $title,
    'description' => $description,
    'default_language' => $language
];

if (!is_dir('../config')) {
    mkdir('../config', 0755, true);
}

$written = file_put_contents('../config/website_settings.json', json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($written !== false) {
    $_SESSION['website'] = $settings;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Datei konnte nicht gespeichert werden.']);
}
