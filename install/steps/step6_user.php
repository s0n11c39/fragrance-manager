<?php
// Prüfe, ob SQL-Import & DB-Config vorhanden
$dbConfig = $_SESSION['db'] ?? null;
if (!$dbConfig || !file_exists('../config/db_config.json')) {
    echo "<div class='alert alert-danger'>Keine Datenbankverbindung vorhanden.</div>";
    echo "<a href='?step=3' class='btn btn-secondary'>Zurück zur DB</a>";
    return;
}

$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>DB-Verbindung fehlgeschlagen: " . htmlspecialchars($e->getMessage()) . "</div>";
    return;
}

// Formularverarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        $error = "Die Passwörter stimmen nicht überein.";
    } elseif (strlen($password) < 6) {
        $error = "Das Passwort muss mindestens 6 Zeichen lang sein.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        try {
            $stmt->execute([$username, $email, $hash]);

            // Install abgeschlossen
            file_put_contents('../config/installed.lock', 'installed: ' . date('Y-m-d H:i:s'));
            session_destroy();
            header('Location: done.php');
            exit;
        } catch (PDOException $e) {
            $error = "Fehler beim Speichern: " . $e->getMessage();
        }
    }
}
?>

<h3>👤 Admin-Benutzer erstellen</h3>
<p>Lege jetzt dein Administrator-Konto an, mit dem du dich später einloggen kannst.</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Benutzername</label>
        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">E-Mail-Adresse</label>
        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Passwort</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Passwort wiederholen</label>
        <input type="password" name="password2" class="form-control" required>
    </div>

    <div class="text-end">
        <a href="?step=5" class="btn btn-secondary">Zurück</a>
        <button type="submit" class="btn btn-primary">Konto erstellen & abschließen</button>
    </div>
</form>
