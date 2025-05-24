<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$baseUrl = rtrim($data['base_url'] ?? '', '/');
$rootPath = rtrim($data['root_path'] ?? '', '/');

if (!$baseUrl || !$rootPath) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Eingaben.']);
    exit;
}

$pathData = [
    'base_url' => $baseUrl,
    'root_path' => $rootPath
];

if (!is_dir('../config')) {
    mkdir('../config', 0755, true);
}

$saved = file_put_contents('../config/path_config.json', json_encode($pathData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

if ($saved !== false) {
    $_SESSION['base_url'] = $baseUrl;
    $_SESSION['root_path'] = $rootPath;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Pfadkonfiguration konnte nicht gespeichert werden.']);
}
