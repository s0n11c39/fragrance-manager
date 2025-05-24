<?php
// admin/settings/notification_settings.php
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';
$settings = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
?>

<form method="post" id="active-settings-form" action="save_notification_settings.php">
  <div class="mb-3">
    <label class="form-label">Absenderadresse (From)</label>
    <input type="email" name="email_from" class="form-control" required
           value="<?= htmlspecialchars($settings['email_from'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Absendername</label>
    <input type="text" name="email_from_name" class="form-control"
           value="<?= htmlspecialchars($settings['email_from_name'] ?? '') ?>">
  </div>

  <hr>

  <div class="mb-3">
    <label class="form-label">SMTP-Server</label>
    <input type="text" name="smtp_host" class="form-control"
           value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">SMTP-Port</label>
    <input type="number" name="smtp_port" class="form-control"
           value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Verschlüsselung</label>
    <select name="smtp_secure" class="form-select">
      <option value="">Keine</option>
      <option value="tls" <?= ($settings['smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
      <option value="ssl" <?= ($settings['smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">SMTP-Benutzername</label>
    <input type="text" name="smtp_user" class="form-control"
           value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">SMTP-Passwort</label>
    <input type="password" name="smtp_pass" class="form-control"
           value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>">
  </div>
</form>
