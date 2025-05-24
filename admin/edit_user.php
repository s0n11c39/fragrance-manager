<?php
session_start();
require_once "db.php";
require_once "alerts.php";

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Zugriff prüfen
if (!isset($_SESSION["admin"]) || $_SESSION["admin_role"] !== "admin") {
    setAlert("error", $lang['user_edit_no_permission'] ?? "Du hast keine Berechtigung, Benutzer zu bearbeiten.");
    header("Location: users.php");
    exit;
}

// Speichern (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id         = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $first_name = trim($_POST["first_name"] ?? '');
    $last_name  = trim($_POST["last_name"] ?? '');
    $username   = trim($_POST["username"] ?? '');
    $email      = trim($_POST["email"] ?? '');
    $role       = $_POST["role"] ?? 'user';
    $password   = $_POST["password"] ?? '';

    if ($id > 0 && $first_name && $last_name && $username && $email && $role) {
        // Prüfen, ob Benutzername bereits existiert (außer bei sich selbst)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $id]);
        if ($stmt->fetchColumn() > 0) {
            setAlert("error", $lang['user_edit_username_exists'] ?? "Benutzername existiert bereits.");
        } else {
            if (!empty($password)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, role = ?, password = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $username, $email, $role, $hashed, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $username, $email, $role, $id]);
            }
            setAlert("success", $lang['user_edit_success'] ?? "Benutzer wurde erfolgreich aktualisiert.");
        }
    } else {
        setAlert("error", $lang['user_edit_missing_fields'] ?? "Bitte alle Felder ausfüllen.");
    }

    header("Location: users.php");
    exit;
} else {
    // Bei direktem Aufruf ohne POST
    header("Location: users.php");
    exit;
}
