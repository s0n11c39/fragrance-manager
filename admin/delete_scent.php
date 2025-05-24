<?php
session_start();
require_once "db.php";
require_once "alerts.php";

// Sprachdatei laden
require_once __DIR__ . '/../lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Zugriffsschutz
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", $lang['scent_delete_no_permission'] ?? "Du hast keine Berechtigung, diesen Duft zu löschen.");
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];

    // Duft holen
    $stmt = $pdo->prepare("SELECT code, inspired_by, qr_code FROM scents WHERE id = ?");
    $stmt->execute([$id]);
    $scent = $stmt->fetch();

    if ($scent) {
        // QR-Code löschen
        $qrCodeFile = $scent['qr_code'];
        if (!empty($qrCodeFile) && file_exists($qrCodeFile)) {
            unlink($qrCodeFile);
        }

        // Datenbank-Einträge löschen
        $pdo->prepare("DELETE FROM scent_notes WHERE scent_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM scent_attributes WHERE scent_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM scents WHERE id = ?")->execute([$id]);

        setAlert(
            "success",
            sprintf($lang['scent_delete_success'], htmlspecialchars($scent['code']), htmlspecialchars($scent['inspired_by']))
        );
    } else {
        setAlert("error", $lang['scent_delete_not_found'] ?? "Der ausgewählte Duft konnte nicht gefunden werden.");
    }
}

header("Location: dashboard.php");
exit;