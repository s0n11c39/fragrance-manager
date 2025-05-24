<?php
session_start();

// Session-Daten löschen
$_SESSION = [];

// Session-Cookie explizit löschen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session zerstören
session_destroy();

// Weiterleitung zur Login-Seite
header("Location: index.php");
exit;