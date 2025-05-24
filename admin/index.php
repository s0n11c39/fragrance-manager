<?php
// Session-Sicherheit VOR session_start
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
// ini_set('session.cookie_secure', 1); // Nur bei HTTPS aktivieren

session_start();

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// CSRF-Token generieren (einmalig pro Session)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "db.php"; // DB-Verbindung

$error = "";
$username_value = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $csrf_token = $_POST["csrf_token"] ?? '';
    $username_value = htmlspecialchars($username);

    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error = $lang['login_error_csrf'] ?? "Ungültiger Sicherheits-Token.";
    } elseif ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["admin"] = $user["id"];
            $_SESSION["admin_name"] = trim($user["first_name"] . " " . $user["last_name"]);
            $_SESSION["admin_email"] = $user["email"];
            $_SESSION["admin_role"] = $user["role"]; // z.B. 'admin', 'editor', 'user'

            header("Location: dashboard.php");
            exit;
        } else {
            $error = $lang['login_error_invalid'] ?? "Benutzername oder Passwort ist falsch.";
        }
    } else {
        $error = $lang['login_error_required'] ?? "Bitte alle Felder ausfüllen.";
    }
}
?>
<!doctype html>
<html lang="<?= $lang['lang_code'] ?? 'de' ?>">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= $lang['login_title'] ?? 'Admin Login' ?></title>
    <link href="../assets/css/tabler.min.css" rel="stylesheet"/>
</head>
<body class="d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="#" class="navbar-brand">
                <img src="../assets/img/logo.svg" width="210" height="100" alt="Logo" class="navbar-brand-image">
            </a>
        </div>

        <form class="card card-md" method="post" autocomplete="off">
            <div class="card-body">
                <h2 class="card-title text-center mb-4"><?= $lang['login_title'] ?></h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label"><?= $lang['login_username'] ?></label>
                    <input type="text" class="form-control" name="username" value="<?= $username_value ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= $lang['login_password'] ?></label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100"><?= $lang['login_btn'] ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
