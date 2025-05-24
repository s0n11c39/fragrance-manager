<?php
header("Content-Type: application/json");
require_once "admin/db.php";

$scents_stmt = $pdo->query("SELECT * FROM scents ORDER BY id DESC");
$scents = [];

while ($row = $scents_stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row["id"];
    
    // Hole den use_pyramid-Wert (Standard: 1 = Duftpyramide)
    $usePyramid = (int)($row['use_pyramid'] ?? 1);
    
    // Pyramid-Array basierend auf use_pyramid initialisieren
    if ($usePyramid === 1) {
        $row["pyramid"] = ["Kopf" => [], "Herz" => [], "Basis" => []];
    } else {
        $row["pyramid"] = ["All" => []];
    }
    
    $row["diagramme"] = ["Dufttyp" => [], "Stil" => [], "Jahreszeit" => [], "Anlass" => []];

    // Noten laden (Duftpyramide oder allgemeine Duftnoten)
    $notes_stmt = $pdo->prepare("SELECT n.note_name, n.type, i.image 
                                 FROM scent_notes n
                                 LEFT JOIN notes i ON n.note_name = i.name
                                 WHERE n.scent_id = ?");
    $notes_stmt->execute([$id]);
    while ($note = $notes_stmt->fetch(PDO::FETCH_ASSOC)) {
        $type = $note["type"];
        $note_name = $note["note_name"];
        $img_file = $note["image"];
        
        // Falls Bild vorhanden → verwenden, sonst Default
        $img_url = "assets/img/notes/" . ($img_file ?: "default.webp");
        
        // Füge den Noteneintrag in das pyramid-Array ein, sofern der Schlüssel existiert.
        // Wenn der Datensatz allgemeine Duftnoten verwendet (use_pyramid==0), 
        // sollte in der DB der Typ 'All' verwendet worden sein.
        if (!isset($row["pyramid"][$type])) {
            // Optional: Falls ein ungültiger Typ kommt, setze als 'All'
            $type = "All";
        }
        $row["pyramid"][$type][] = ["name" => $note_name, "img" => $img_url];
    }

    // Diagramm-Daten laden
    $attr_stmt = $pdo->prepare("SELECT * FROM scent_attributes WHERE scent_id = ?");
    $attr_stmt->execute([$id]);
    while ($attr = $attr_stmt->fetch(PDO::FETCH_ASSOC)) {
        $cat = $attr["category"];
        $row["diagramme"][$cat][] = [
            "category" => $attr["label"],
            "value" => (int)$attr["value"]
        ];
    }
    
    $scents[] = [
        "code" => $row["code"],
        "inspired_by" => $row["inspired_by"],
        "gender" => $row["gender"],
        "description" => $row["direction"],
        "pyramid" => $row["pyramid"],
        "diagramme" => $row["diagramme"],
        "use_pyramid" => $usePyramid
    ];
}

echo json_encode($scents);
?>
