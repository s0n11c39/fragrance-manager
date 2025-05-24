<?php

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Fehlertext via GET oder Session empfangen
$message = $_GET['msg'] ?? ($lang['error_unknown'] ?? 'Ein unbekannter Fehler ist aufgetreten.');
?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Fehler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/tabler.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
</head>
<body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">⚠️</div>
                <p class="empty-title"><?= $lang['error_title'] ?></p>
                <p class="empty-subtitle text-muted">
                    <?= htmlspecialchars($message) ?>
                </p>
                <div class="empty-action">
                    <a href="dashboard.php" class="btn btn-primary">
                    <?= $lang['error_back_dashboard'] ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
