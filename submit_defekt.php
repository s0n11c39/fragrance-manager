<?php
// submit_defekt.php
session_start();
require_once "admin/db.php"; // Datenbankverbindung (anpassen, falls nötig)

// Sprachdatei laden
require_once __DIR__ . '/lang/lang.php'; //

// Hilfsfunktion für sicheren Dateinamen
function slugify($text) {
    $text = strtolower($text);
    $text = str_replace(["ä", "ö", "ü", "ß", " "], ["ae", "oe", "ue", "ss", "_"], $text);
    $text = preg_replace('/[^a-z0-9_]/', '', $text);
    return $text;
}

// POST-Daten
$name     = trim($_POST["name"] ?? '');
$email    = trim($_POST["email"] ?? '');
$duft     = trim($_POST["duft"] ?? '');
$problem  = trim($_POST["problem"] ?? '');
$message  = trim($_POST["message"] ?? '');
$image    = null;

// Kundenname und E-Mail normalisieren
$normalizedEmail = strtolower(trim($email));
$normalizedName = preg_replace('/\s+/', ' ', strtolower(trim($name))); // Mehrfache Leerzeichen + lowercase

// Kunden-ID anhand normalisierter Werte suchen
$stmt = $pdo->prepare("
    SELECT id FROM customers 
    WHERE LOWER(TRIM(email)) = ? AND LOWER(TRIM(name)) = ?
    LIMIT 1
");
$stmt->execute([$normalizedEmail, $normalizedName]);
$customerId = $stmt->fetchColumn();

// Falls Kunde nicht existiert, neuen Datensatz anlegen mit Originalwerten
if (!$customerId) {
    $stmt = $pdo->prepare("INSERT INTO customers (name, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
    $customerId = $pdo->lastInsertId();
}


// Pflichtfelder prüfen
if (!$name || !$email || !$duft || !$problem) {
    die($lang['defect_missing_fields'] ?? "Bitte fülle alle Pflichtfelder aus.");
}

// Bild hochladen (optional)
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/uploads/defekte/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Ordner erstellen, falls nicht vorhanden
    }

    $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $safeName = slugify($duft . '-' . uniqid()) . '.' . $extension;
    $targetPath = $uploadDir . $safeName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        $image = $safeName;
    }
}

// Token erzeugen
$accessToken = bin2hex(random_bytes(32)); // 64 Zeichen sicherer Zugriffstoken

// In DB speichern
$stmt = $pdo->prepare("INSERT INTO defects (customer_id, name, email, duft, problem, message, image, access_token)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$customerId, $name, $email, $duft, $problem, $message, $image, $accessToken]);


header("Location: defekt_success.php?token=$accessToken");
exit;
