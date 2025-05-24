<?php
require_once dirname(__DIR__, 2) . '/lang/lang.php';
if (!isset($lang)) {
   die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Lade gespeicherte DB-Daten (wenn vorhanden)
$configPath = __DIR__ . '/../../config/db_config.json';
$host = $dbname = $user = '';

if (file_exists($configPath)) {
    $config = json_decode(file_get_contents($configPath), true);
    $host   = htmlspecialchars($config['host'] ?? '');
    $dbname = htmlspecialchars($config['dbname'] ?? '');
    $user   = htmlspecialchars($config['user'] ?? '');
    // pass NICHT vorausfüllen
}
?>

<h2 class="mb-4"><?= $lang['db_heading'] ?></h2>

<form id="active-settings-form" action="save_database_settings.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="action" value="save_database_settings">

   <!-- Backup erstellen -->
   <div class="mb-4">
      <h3 class="card-title"><?= $lang['db_backup_title'] ?></h3>
      <p class="text-secondary"><?= $lang['db_backup_description'] ?></p>
      <button type="submit" name="action" value="export" class="btn btn-outline-primary"><?= $lang['db_backup_download'] ?></button>
   </div>

   <hr>

   <!-- Backup importieren -->
   <div class="mb-4">
      <h3 class="card-title"><?= $lang['db_import_title'] ?></h3>
      <p class="text-secondary"><?= $lang['db_import_description'] ?></p>
      <div class="mb-2">
         <input type="file" class="form-control" name="import_file" accept=".sql">
      </div>
      <button type="submit" name="action" value="import" class="btn btn-outline-danger"><?= $lang['db_import_button'] ?></button>
   </div>

   <hr>

   <!-- Verbindung bearbeiten (optional) -->
   <div class="mb-4">
      <h3 class="card-title"><?= $lang['db_connection_title'] ?></h3>
      <p class="text-secondary"><?= $lang['db_connection_description'] ?></p>
      <div class="row g-3">
      <div id="db-connection-result" class="mt-2"></div>
         <div class="col-md-6">
            <label class="form-label"><?= $lang['db_host_label'] ?></label>
            <input type="text" class="form-control" name="db_host" value="<?= $host ?>">
         </div>
         <div class="col-md-6">
            <label class="form-label"><?= $lang['db_name_label'] ?></label>
            <input type="text" class="form-control" name="db_name" value="<?= $dbname ?>">
         </div>
         <div class="col-md-6">
            <label class="form-label"><?= $lang['db_user_label'] ?></label>
            <input type="text" class="form-control" name="db_user" value="<?= $user ?>">
         </div>
         <div class="col-md-6">
            <label class="form-label"><?= $lang['db_pass_label'] ?></label>
            <input type="password" class="form-control" name="db_pass" value="">
         </div>
      </div>
   </div>

   <!-- Verbindung testen -->
<div class="mb-4">
   <button type="button" class="btn btn-outline-success" id="test-db-connection"><?= $lang['db_test_button'] ?></button>
</div>
</form>
