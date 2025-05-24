<?php
session_start();
require_once "alerts.php"; // Alerts-Funktion einbinden

if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", "Du hast keine Berechtigung für diese Aktion.");
    header("Location: notes.php");
    exit;
}

require_once "db.php";

// Zielordner für Bilder
$uploadDir = "../assets/img/notes/";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
    $name = trim($_POST["name"]);

    // 1. Name prüfen
    if ($name === "") {
        $_SESSION["error"] = "Bitte gib einen Namen für die Duftnote an.";
        header("Location: notes.php");
        exit;
    }

    // 2. Prüfen, ob Duftnote schon existiert
    $check = $pdo->prepare("SELECT name FROM notes WHERE name = ?");
    $check->execute([$name]);
    $existingName = $check->fetchColumn();
    if ($existingName) {
        $_SESSION["error"] = "Der Name '" . htmlspecialchars($existingName) . "' ist bereits vergeben.";
        header("Location: notes.php");
        exit;
    }

    // 3. Bildprüfung – jetzt verpflichtend!
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $_SESSION["error"] = "Bitte lade ein Bild für die Duftnote hoch.";
        header("Location: notes.php");
        exit;
    }

    // 4. Bildverarbeitung
    $tmpName = $_FILES["image"]["tmp_name"];
    $originalName = $_FILES["image"]["name"];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowed = ["webp", "jpg", "jpeg", "png"];
    if (!in_array($ext, $allowed)) {
        $_SESSION["error"] = "Ungültiges Bildformat. Erlaubt sind: .webp, .jpg, .jpeg, .png";
        header("Location: notes.php");
        exit;
    }

    // Slug für Dateiname generieren
    $slug = strtolower($name);
    $slug = str_replace(
        ["ä", "ö", "ü", "ß", " ", "-", "é", "è", "à", "á", "ô", "î", "â", "ë", "ê", "ç"],
        ["ae", "oe", "ue", "ss", "_", "_", "e", "e", "a", "a", "o", "i", "a", "e", "e", "c"],
        $slug
    );
    $slug = preg_replace('/[^a-z0-9_]/', '', $slug);

    $imageFileName = $slug . "." . $ext;
    $targetFile = $uploadDir . $imageFileName;

    // Datei verschieben
    if (!move_uploaded_file($tmpName, $targetFile)) {
        $_SESSION["error"] = "Fehler beim Hochladen des Bildes.";
        header("Location: notes.php");
        exit;
    }

    // 5. In DB speichern
    try {
        $stmt = $pdo->prepare("INSERT INTO notes (name, image) VALUES (?, ?)");
        $stmt->execute([$name, $imageFileName]);
        $_SESSION["success"] = "Die Duftnote \"" . htmlspecialchars($name) . "\" wurde erfolgreich hinzugefügt.";
    } catch (PDOException $e) {
        $_SESSION["error"] = "Fehler beim Speichern in der Datenbank.";
    }
}

header("Location: notes.php");
exit;
