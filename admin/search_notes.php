<?php
require_once "db.php";
header("Content-Type: application/json");

$term = isset($_GET["q"]) ? trim($_GET["q"]) : "";

$stmt = $pdo->prepare("SELECT * FROM notes WHERE name LIKE ? ORDER BY name ASC");
$stmt->execute(["%" . $term . "%"]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
