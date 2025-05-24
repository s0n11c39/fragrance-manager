<?php
session_start();

require_once "alerts.php";
require_once "db.php";

// Sprachdatei laden
require_once __DIR__ . '/../lang/lang.php'; // Kürzer & klarer als dirname(__DIR__)
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Zugriffsrechte prüfen
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", $lang['note_delete_no_permission'] ?? "Du hast keine Berechtigung.");
    header("Location: notes.php");
    exit;
}

// Prüfen ob POST-Request & ID gesetzt ist
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];

    // Eintrag abrufen
    $stmt = $pdo->prepare("SELECT name, image FROM notes WHERE id = ?");
    $stmt->execute([$id]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($note) {
        // Bild löschen, wenn vorhanden
        if (!empty($note["image"])) {
            $imagePath = realpath(__DIR__ . "/../assets/img/notes/" . $note["image"]);
            $imageDir  = realpath(__DIR__ . "/../assets/img/notes");

            if ($imagePath && strpos($imagePath, $imageDir) === 0 && file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Eintrag löschen
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->execute([$id]);

        // Erfolgsnachricht
        $_SESSION["success"] = sprintf($lang['note_delete_success'], htmlspecialchars($note["name"]));
    }
}

header("Location: notes.php");
exit;
