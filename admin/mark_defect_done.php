<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["admin"])) {
    die("Nicht autorisiert.");
}

$defectId = (int) ($_POST['defect_id'] ?? 0);
$newStatus = (int) ($_POST['done'] ?? 0);

$stmt = $pdo->prepare("UPDATE defects SET is_done = ? WHERE id = ?");
$stmt->execute([$newStatus, $defectId]);

header("Location: defects.php");
exit;
