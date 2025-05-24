<?php
session_start();
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    header("Location: index.php");
    exit;
}

require_once "db.php";
require_once "alerts.php";

// Hilfsfunktionen
function parseAttributes($labels, $values) {
    $parsed = [];
    if (is_array($labels) && is_array($values)) {
        foreach ($labels as $i => $label) {
            $label = trim($label);
            $value = isset($values[$i]) ? (int)trim($values[$i]) : 0;
            if ($label !== "" && is_numeric($value)) {
                $parsed[] = ['label' => $label, 'value' => $value];
            }
        }
    }
    return $parsed;
}

function saveAttributes($pdo, $scent_id, $category, $attributes) {
    $pdo->prepare("DELETE FROM scent_attributes WHERE scent_id = ? AND category = ?")
        ->execute([$scent_id, $category]);
    foreach ($attributes as $attr) {
        $stmt = $pdo->prepare("INSERT INTO scent_attributes (scent_id, category, label, value) VALUES (?, ?, ?, ?)");
        $stmt->execute([$scent_id, $category, $attr["label"], $attr["value"]]);
    }
}

function saveNotes($pdo, $scent_id, $type, $notes) {
    // Alte Notes dieses Typs löschen
    $pdo->prepare("DELETE FROM scent_notes WHERE scent_id = ? AND type = ?")
        ->execute([$scent_id, $type]);

    foreach ($notes as $note) {
        $note = trim($note);
        if ($note !== '') {
            $stmt = $pdo->prepare("INSERT INTO scent_notes (scent_id, type, note_name) VALUES (?, ?, ?)");
            $stmt->execute([$scent_id, $type, $note]);
        }
    }
}

// POST-Daten einlesen
$id           = (int)($_POST["id"] ?? 0);
$code         = trim($_POST["code"] ?? '');
$inspired_by  = trim($_POST["inspired_by"] ?? '');
$gender       = $_POST["gender"] ?? '';
$description  = trim($_POST["description"] ?? '');

// [NEU] Switch: 1 = Duftpyramide, 0 = Allgemeine Noten
$usePyramid   = isset($_POST['use_pyramid']) ? 1 : 0;

// Kopf/Herz/Basis
$notes_top    = $_POST["notes_top"]   ?? [];
$notes_heart  = $_POST["notes_heart"] ?? [];
$notes_base   = $_POST["notes_base"]  ?? [];
// Allgemeine Noten
$notes_all    = $_POST["notes_all"]   ?? [];

// Pflichtfelder prüfen
if (!$id || !$inspired_by || !$gender || !$code) {
    setAlert("error", "Fehlende Pflichtfelder.");
    header("Location: dashboard.php");
    exit;
}

// Vorhandene Beschreibung laden, falls leer
if ($description === '') {
    $stmt = $pdo->prepare("SELECT description FROM scents WHERE id = ?");
    $stmt->execute([$id]);
    $description = $stmt->fetchColumn() ?? '';
}

// Code auf Duplikate prüfen
$stmt = $pdo->prepare("SELECT id FROM scents WHERE code = ? AND id != ?");
$stmt->execute([$code, $id]);
if ($stmt->fetch()) {
    setAlert("error", "Fehler: Der Code <strong>" . htmlspecialchars($code) . "</strong> ist bereits einem anderen Duft zugewiesen.");
    header("Location: dashboard.php");
    exit;
}

// [NEU] scents aktualisieren (inkl. use_pyramid)
$stmt = $pdo->prepare("
    UPDATE scents
    SET code = ?, inspired_by = ?, gender = ?, description = ?, direction = ?, use_pyramid = ?
    WHERE id = ?
");
$stmt->execute([$code, $inspired_by, $gender, $description, $description, $usePyramid, $id]);

// [NEU] Je nach Switch: Entweder Pyramide oder All
if ($usePyramid == 1) {
    // Kopf/Herz/Basis-Noten
    saveNotes($pdo, $id, 'Kopf',  $notes_top);
    saveNotes($pdo, $id, 'Herz',  $notes_heart);
    saveNotes($pdo, $id, 'Basis', $notes_base);

    // Falls du zuvor evtl. "All"-Noten angelegt hast, könntest du sie hier zusätzlich löschen:
    $pdo->prepare("DELETE FROM scent_notes WHERE scent_id = ? AND type = 'All'")->execute([$id]);

} else {
    // Allgemeine Noten
    saveNotes($pdo, $id, 'All', $notes_all);

    // Umgekehrt alte Kopf/Herz/Basis-Einträge weg:
    $pdo->prepare("DELETE FROM scent_notes WHERE scent_id = ? AND type IN ('Kopf','Herz','Basis')")
         ->execute([$id]);
}

// Dufttyp
$dufttyp = parseAttributes($_POST["dufttyp_label"] ?? [], $_POST["dufttyp_value"] ?? []);
saveAttributes($pdo, $id, 'Dufttyp', $dufttyp);

// Stil
$stil = [
    ["label" => "Herren",    "value" => (int)($_POST["stil_Herren"]     ?? 0)],
    ["label" => "Damen",     "value" => (int)($_POST["stil_Damen"]      ?? 0)],
    ["label" => "Klassisch", "value" => (int)($_POST["stil_Klassisch"]  ?? 0)],
    ["label" => "Modern",    "value" => (int)($_POST["stil_Modern"]     ?? 0)],
];
saveAttributes($pdo, $id, 'Stil', $stil);

// Jahreszeit
$season = [
    ["label" => "Frühling", (int)($_POST["season_Fruehling"] ?? 0)],
    ["label" => "Sommer",   (int)($_POST["season_Sommer"]   ?? 0)],
    ["label" => "Herbst",   (int)($_POST["season_Herbst"]   ?? 0)],
    ["label" => "Winter",   (int)($_POST["season_Winter"]   ?? 0)],
];
saveAttributes($pdo, $id, 'Jahreszeit', $season);

// Anlass
$occasion = [
    ["label" => "Täglich",  (int)($_POST["occasion_Taeglich"]  ?? 0)],
    ["label" => "Freizeit", (int)($_POST["occasion_Freizeit"]  ?? 0)],
    ["label" => "Ausgehen", (int)($_POST["occasion_Ausgehen"]  ?? 0)],
    ["label" => "Arbeit",   (int)($_POST["occasion_Arbeit"]    ?? 0)],
    ["label" => "Abend",    (int)($_POST["occasion_Abend"]     ?? 0)],
    ["label" => "Sport",    (int)($_POST["occasion_Sport"]     ?? 0)],
];
saveAttributes($pdo, $id, 'Anlass', $occasion);

// Erfolgsmeldung
setAlert("success", "Der Duft <strong>" . htmlspecialchars($inspired_by) . "</strong> wurde erfolgreich aktualisiert.");
header("Location: dashboard.php");
exit;
