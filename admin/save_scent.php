<?php
session_start();
require_once "alerts.php"; // Alerts-Funktion einbinden

// QR-Code Bibliothek einbinden
require_once "../libs/phpqrcode/qrlib.php";

// Rolle prüfen
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", "Du hast keine Berechtigung, einen Duft hinzuzufügen.");
    header("Location: dashboard.php");
    exit;
}

require_once "db.php"; // Verbindung zur MySQL-Datenbank

$inspired_by = $_POST["inspired_by"] ?? '';
$gender = $_POST["gender"] ?? '';
$description = $_POST["description"] ?? '';
$direction = $description;
$manual_code = trim($_POST["code"] ?? '');

// Switch abfragen: Wenn Checkbox gesetzt, dann 1, sonst 0
$usePyramid = isset($_POST['use_pyramid']) ? 1 : 0;

// Pflichtfelder prüfen
if (!$inspired_by || !$gender) {
    setAlert("error", "Bitte fülle alle Pflichtfelder aus.");
    header("Location: dashboard.php");
    exit;
}

// Code erzeugen (manuell oder automatisch)
if ($manual_code !== '') {
    $code = $manual_code;
} else {
    $prefix = $gender === "Herren" ? "M" : ($gender === "Damen" ? "W" : "U");
    $stmt = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(code, 3) AS UNSIGNED)) AS max_num FROM scents WHERE code LIKE ?");
    $stmt->execute([$prefix . "-%"]);
    $max = $stmt->fetchColumn();
    $next_num = str_pad(($max + 1), 3, "0", STR_PAD_LEFT);
    $code = $prefix . "-" . $next_num;
}

try {
    // Insert mit use_pyramid
    $stmt = $pdo->prepare("
        INSERT INTO scents (code, name, inspired_by, gender, description, direction, use_pyramid)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $code,
        $inspired_by,
        $inspired_by,
        $gender,
        $description,
        $direction,
        $usePyramid
    ]);
    $scent_id = $pdo->lastInsertId();

    // Gemeinsame Funktion zum Einfügen von Noten
    function save_notes($pdo, $scent_id, $type, $notes_array) {
        foreach ($notes_array as $note) {
            $note = trim($note);
            if (!empty($note)) {
                $stmt = $pdo->prepare("INSERT INTO scent_notes (scent_id, type, note_name) VALUES (?, ?, ?)");
                $stmt->execute([$scent_id, $type, $note]);
            }
        }
    }

    // [GEÄNDERT] Unterscheide: Duftpyramide (Kopf/Herz/Basis) oder Allgemeine Noten (All)
    if ($usePyramid) {
        // Switch == an => Kopf/Herz/Basis
        save_notes($pdo, $scent_id, 'Kopf',  $_POST["notes_top"]   ?? []);
        save_notes($pdo, $scent_id, 'Herz',  $_POST["notes_heart"] ?? []);
        save_notes($pdo, $scent_id, 'Basis', $_POST["notes_base"]  ?? []);
    } else {
        // Switch == aus => All
        save_notes($pdo, $scent_id, 'All',   $_POST["notes_all"]   ?? []);
    }

    // Eigenschaften speichern
    function save_attributes_array($pdo, $scent_id, $category, $labels, $values) {
        if (!is_array($labels) || !is_array($values)) return;

        foreach ($labels as $index => $label) {
            $label = trim($label);
            $value = isset($values[$index]) ? (int)trim($values[$index]) : 0;

            if ($label !== "" && is_numeric($value)) {
                $stmt = $pdo->prepare("INSERT INTO scent_attributes (scent_id, category, label, value) VALUES (?, ?, ?, ?)");
                $stmt->execute([$scent_id, $category, $label, $value]);
            }
        }
    }

    // Dufttyp
    save_attributes_array($pdo, $scent_id, 'Dufttyp',
        $_POST["dufttyp_label"] ?? [],
        $_POST["dufttyp_value"] ?? []
    );

    // Stil
    $stil_keys = ["Herren", "Damen", "Klassisch", "Modern"];
    $stil_labels = [];
    $stil_values = [];
    foreach ($stil_keys as $key) {
        $val = $_POST["stil_$key"] ?? '';
        if ($val !== '') {
            $stil_labels[] = $key;
            $stil_values[] = $val;
        }
    }
    save_attributes_array($pdo, $scent_id, 'Stil', $stil_labels, $stil_values);

    // Jahreszeit
    $season_keys = ["Fruehling", "Sommer", "Herbst", "Winter"];
    $season_labels = ["Frühling", "Sommer", "Herbst", "Winter"];
    $season_labels_clean = [];
    $season_values = [];
    foreach ($season_keys as $i => $key) {
        $val = $_POST["season_$key"] ?? '';
        if ($val !== '') {
            $season_labels_clean[] = $season_labels[$i];
            $season_values[] = $val;
        }
    }
    save_attributes_array($pdo, $scent_id, 'Jahreszeit', $season_labels_clean, $season_values);

    // Anlass
    $occasion_keys = ["Taeglich", "Freizeit", "Ausgehen", "Arbeit", "Abend", "Sport"];
    $occasion_labels_clean = ["Täglich", "Freizeit", "Ausgehen", "Arbeit", "Abend", "Sport"];
    $occasion_labels = [];
    $occasion_values = [];
    foreach ($occasion_keys as $i => $key) {
        $val = $_POST["occasion_$key"] ?? '';
        if ($val !== '') {
            $occasion_labels[] = $occasion_labels_clean[$i];
            $occasion_values[] = $val;
        }
    }
    save_attributes_array($pdo, $scent_id, 'Anlass', $occasion_labels, $occasion_values);



        // QR-Code Pfad und URL für den Duft
    $qrCodeDirectory = "../assets/qrcodes/";  // Verzeichnis, in dem die QR-Codes gespeichert werden
    if (!is_dir($qrCodeDirectory)) {
        mkdir($qrCodeDirectory, 0777, true);  // Falls der Ordner noch nicht existiert, erstellen
    }

    // URL zum Duft (die URL muss auf deine Seite angepasst werden)
    $qrCodeUrl = "https://parfum.lcree.de/index.php?code=" . $code;

    // QR-Code Bildname basierend auf dem Duftcode
    $qrCodeFile = $qrCodeDirectory . $code . ".png";

    // QR-Code generieren
    // Hier nimmst du die verschiedenen Optionen vor:
    QRcode::png($qrCodeUrl, $qrCodeFile, QR_ECLEVEL_Q, 6, 1);  // Fehlerkorrektur M, Größe 6, Margin 4

    // Den QR-Code Pfad in der Datenbank speichern (optional)
    $stmt = $pdo->prepare("UPDATE scents SET qr_code = ? WHERE id = ?");
    $stmt->execute([$qrCodeFile, $scent_id]);



    // Erfolgsmeldung
    setAlert("success", "Der Duft <strong>" . htmlspecialchars($inspired_by) . "</strong> wurde erfolgreich hinzugefügt.");

} catch (PDOException $e) {
    setAlert("error", "Fehler beim Hinzufügen des Dufts. Bitte versuche es erneut.");
}

header("Location: dashboard.php");
exit;
?>
