<?php

// Ladet Die Duftnoten für TomSelect 

require_once "db.php";
header("Content-Type: application/json");

$stmt = $pdo->query("SELECT name, image FROM notes ORDER BY name ASC");
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pfad zum Bildordner
$basePath = "../assets/img/notes/";

$formatted = [];
foreach ($notes as $note) {
    $formatted[] = [
        "value" => $note["name"],
        "text" => $note["name"],
        "image" => $basePath . $note["image"]
    ];
}

echo json_encode($formatted);
