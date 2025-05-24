<?php
// Sprache aus JSON-Konfiguration laden
$configPath = dirname(__DIR__) . '/config/website_settings.json';
$settings = [];

if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

$language = $settings['default_language'] ?? 'de';

// Sprachdatei laden
$langFile = __DIR__ . "/{$language}.php";
if (file_exists($langFile)) {
    require_once $langFile;
} else {
    // Fallback: Deutsch
    require_once __DIR__ . "/de.php";
}
