<?php
session_start();
require_once "db.php";
require_once "../libs/phpqrcode/qrlib.php";
require_once "alerts.php";

if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", "Keine Berechtigung.");
    header("Location: dashboard.php");
    exit;
}

$id = (int) ($_POST["id"] ?? 0);

$stmt = $pdo->prepare("SELECT code FROM scents WHERE id = ?");
$stmt->execute([$id]);
$scent = $stmt->fetch();

if (!$scent || empty($scent["code"])) {
    setAlert("error", "Duft nicht gefunden oder Code fehlt.");
    header("Location: dashboard.php");
    exit;
}

$code = $scent["code"];
$qrCodeDirectory = "../assets/qrcodes/";
if (!is_dir($qrCodeDirectory)) {
    mkdir($qrCodeDirectory, 0777, true);
}

$qrCodeUrl  = "https://parfum.lcree.de/index.php?code=" . urlencode($code);
$qrCodeFile = $qrCodeDirectory . $code . ".png";

QRcode::png($qrCodeUrl, $qrCodeFile, QR_ECLEVEL_L, 4);

// Update Pfad in DB
$stmt = $pdo->prepare("UPDATE scents SET qr_code = ? WHERE id = ?");
$stmt->execute([$qrCodeFile, $id]);

setAlert("success", "QR-Code für Duft <strong>$code</strong> wurde erstellt.");
header("Location: dashboard.php");
exit;
