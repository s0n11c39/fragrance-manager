<?php
require_once "admin/db.php";

$token = $_POST["token"] ?? '';
$message = trim($_POST["message"] ?? '');

if (!preg_match('/^[a-f0-9]{64}$/', $token) || $message === '') {
    die("Ungültige Anfrage.");
}

// Defektmeldung laden
$stmt = $pdo->prepare("SELECT id FROM defects WHERE access_token = ?");
$stmt->execute([$token]);
$defect = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$defect) {
    die("Defektmeldung nicht gefunden.");
}

// Nachricht speichern (user_id ist NULL → Kunde)
$stmt = $pdo->prepare("INSERT INTO defect_messages (defect_id, user_id, message) VALUES (?, NULL, ?)");
$stmt->execute([$defect["id"], $message]);

// Optional: Admin informieren per Mail

header("Location: defekt-status.php?token=$token");
exit;
