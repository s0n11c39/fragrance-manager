<?php
require_once __DIR__ . '/lang/lang.php';

$token = $_GET['token'] ?? null;
if (!$token || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    die("Ungültiger oder fehlender Token.");
}

$link = "https://parfum.lcree.de/defekt-status.php?token=$token";
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['defect_success_title'] ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div style="padding:20px; font-family:sans-serif;">
    <h2><?= $lang['defect_success_title'] ?></h2>
    <p><?= $lang['defect_success_message'] ?></p>
    <p><strong>Dein individueller Link zur Meldung:</strong><br>
      <a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($link) ?></a></p>
    <a href="index.php"><?= $lang['defect_back_home'] ?></a>
  </div>
</body>
</html>
