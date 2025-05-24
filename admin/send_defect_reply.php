<?php
session_start();
require_once "db.php";
require_once "send_email.php";

if (!isset($_SESSION["admin"]) || empty($_POST["defect_id"]) || empty(trim($_POST["message"]))) {
    die("Nicht autorisiert oder unvollständig.");
}

$defectId = (int) $_POST["defect_id"];
$userId   = (int) $_SESSION["admin"];
$message  = trim($_POST["message"]);

// Nachricht speichern
$stmt = $pdo->prepare("INSERT INTO defect_messages (defect_id, user_id, message) VALUES (?, ?, ?)");
$stmt->execute([$defectId, $userId, $message]);

// Kundendaten abrufen
$stmt = $pdo->prepare("SELECT name, email, access_token FROM defects WHERE id = ?");
$stmt->execute([$defectId]);
$defect = $stmt->fetch(PDO::FETCH_ASSOC);

if ($defect) {
    $subject  = "Neue Antwort auf deine Defektmeldung";
    $link     = "https://parfum.lcree.de/defekt-status.php?token=" . $defect["access_token"];
    $logoUrl  = "https://parfum.lcree.de/assets/img/logo2.svg";
    $name     = $defect["name"];
    $preview  = "Neue Antwort: " . mb_substr($message, 0, 90) . "...";

    // HTML mit Template generieren
    ob_start();
    include __DIR__ . '/../templates/defect_reply_template.php';
    $body = ob_get_clean();

    // Plaintext für Vorschau (iOS + Mail-Clients)
    $altBody = "Hallo $name,\n\n"
             . "Neue Antwort auf deine Defektmeldung:\n\n"
             . $message . "\n\n"
             . "Status einsehen: $link\n\n"
             . "Viele Grüße,\nDein L'CREE Team";

    sendCustomMail($defect["email"], $subject, $body, $altBody);
}

header("Location: defects.php");
exit;
