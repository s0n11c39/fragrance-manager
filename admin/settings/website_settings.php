<?php
require_once dirname(__DIR__, 2) . '/lang/lang.php';
if (!isset($lang)) {
   die('⚠️ Sprachdatei konnte nicht geladen werden.');
}
// Konfiguration laden
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';
$settings = [];

if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

// Fallback-Werte
$siteTitle = $settings['site_title'] ?? 'Meine Webseite';
$metaDescription = $settings['meta_description'] ?? '';
$defaultLanguage = $settings['default_language'] ?? 'de';

// Korrigierter Logo-Pfad: Absolut zur Root-Website
$logoPath = isset($settings['site_logo']) ? '/' . $settings['site_logo'] : '/assets/img/logo2.svg';

?>

<h2 class="mb-4"><?= $lang['website_settings_heading'] ?></h2>

<form id="active-settings-form" action="save_website_settings.php" method="POST" enctype="multipart/form-data">
   <div class="mb-3">
      <label class="form-label"><?= $lang['site_title_label'] ?></label>
      <input type="text" class="form-control" name="site_title" value="<?= htmlspecialchars($siteTitle) ?>">
   </div>

   <div class="mb-3">
      <label class="form-label"><?= $lang['meta_description_label'] ?></label>
      <textarea class="form-control" name="meta_description" rows="3"><?= htmlspecialchars($metaDescription) ?></textarea>
   </div>

   <div class="mb-3">
      <label class="form-label"><?= $lang['site_logo_label'] ?></label>
      <input type="file" class="form-control" name="site_logo" id="site_logo_input" accept="image/*">
      <small class="form-hint"><?= $lang['current_logo_hint'] ?></small><br>
      <img id="logo_preview" src="<?= htmlspecialchars($logoPath) ?>" alt="Aktuelles Logo" style="height:40px;">
   </div>

   <div class="mb-3">
      <label class="form-label"><?= $lang['language_label'] ?></label>
      <select class="form-select" name="default_language">
         <option value="de" <?= $defaultLanguage === 'de' ? 'selected' : '' ?>><?= $lang['german'] ?></option>
         <option value="en" <?= $defaultLanguage === 'en' ? 'selected' : '' ?>><?= $lang['english'] ?></option>
         <option value="fr" <?= $defaultLanguage === 'fr' ? 'selected' : '' ?>><?= $lang['french'] ?></option>
      </select>
   </div>
</form>
