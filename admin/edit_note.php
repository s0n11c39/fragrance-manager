<?php
session_start();
require_once "alerts.php";

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Zugriffsschutz
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", $lang['note_edit_no_permission'] ?? "Du hast keine Berechtigung für diese Aktion.");
    header("Location: notes.php");
    exit;
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["name"])) {
    $id = (int) $_POST["id"];
    $name = trim($_POST["name"]);

    if ($name === "") {
        $_SESSION["error"] = $lang['note_edit_missing_name'] ?? "Bitte gib einen Namen für die Duftnote an.";
        header("Location: notes.php");
        exit;
    }

    // Prüfen auf Duplikate
    $check = $pdo->prepare("SELECT id FROM notes WHERE name = ? AND id != ?");
    $check->execute([$name, $id]);
    if ($check->fetchColumn()) {
        $_SESSION["error"] = sprintf($lang['note_edit_name_exists'] ?? 'Der Name "%s" ist bereits vergeben.', htmlspecialchars($name));
        header("Location: notes.php");
        exit;
    }

    // Name aktualisieren
    $stmt = $pdo->prepare("UPDATE notes SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);

    // Bild aktualisieren (optional)
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $tmp = $_FILES["image"]["tmp_name"];

        // Slug generieren
        $slug = strtolower($name);
        $slug = str_replace(
            ["ä", "ö", "ü", "ß", " ", "é", "è", "à", "á", "ô", "î", "â", "ë", "ê", "ç"],
            ["ae", "oe", "ue", "ss", "_", "e", "e", "a", "a", "o", "i", "a", "e", "e", "c"],
            $slug
        );
        $slug = preg_replace('/[^a-z0-9_]/', '', $slug);

        $uploadDir = "../assets/img/notes/";
        $targetPath = $uploadDir . $slug . ".webp";

        move_uploaded_file($tmp, $targetPath);
    }

    // Erfolg
    $_SESSION["success"] = sprintf($lang['note_edit_success'] ?? 'Die Duftnote "%s" wurde erfolgreich bearbeitet.', htmlspecialchars($name));
}

header("Location: notes.php");
exit;
