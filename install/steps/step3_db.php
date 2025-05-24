<?php
// Werte aus vorherigem Schritt übernehmen, wenn vorhanden
$defaultHost = 'localhost';
$defaultDb = '';
$defaultUser = '';
$defaultPass = '';

// Wenn POST: Verbindung testen und ggf. speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host']);
    $dbname = trim($_POST['dbname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validierung
    $errors = [];
    if (empty($host)) $errors[] = "Host darf nicht leer sein";
    if (empty($dbname)) $errors[] = "Datenbankname darf nicht leer sein";
    if (empty($username)) $errors[] = "Benutzername darf nicht leer sein";
    
    if (empty($errors)) {
        // Verbindung testen
        try {
            // Erst ohne Datenbank testen
            $pdo = new PDO(
                "mysql:host=$host;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            
            // Prüfen ob Datenbank existiert
            $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
            if (!$stmt->fetch()) {
                // Datenbank erstellen
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
            
            // Verbindung mit Datenbank testen
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );

            // Wenn erfolgreich: JSON schreiben
            $config = [
                'host' => $host,
                'dbname' => $dbname,
                'username' => $username,
                'password' => $password
            ];
            
            // Verzeichnis erstellen falls nicht vorhanden
            if (!file_exists('../config')) {
                mkdir('../config', 0755, true);
            }
            
            if (file_put_contents('../config/db_config.json', json_encode($config, JSON_PRETTY_PRINT))) {
                // Session für spätere Schritte
                session_start();
                $_SESSION['db'] = $config;
                $_SESSION['db_connected'] = true;
                header('Location: ?step=4');
                exit;
            } else {
                $errors[] = "Konfigurationsdatei konnte nicht gespeichert werden";
            }
        } catch (PDOException $e) {
            $errors[] = "Datenbankfehler: " . $e->getMessage();
        }
    }
}
?>

<h3>🗄️ Datenbankverbindung</h3>
<p>Gib die Zugangsdaten für deine MySQL-Datenbank ein. Die Datenbank wird automatisch erstellt, falls sie nicht existiert.</p>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <div class="mb-3">
        <label class="form-label">Host</label>
        <input type="text" name="host" class="form-control" required 
               value="<?= htmlspecialchars($_POST['host'] ?? $defaultHost) ?>"
               placeholder="z.B. localhost oder 127.0.0.1">
        <div class="form-text">Der Hostname deines MySQL-Servers</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Datenbankname</label>
        <input type="text" name="dbname" class="form-control" required 
               value="<?= htmlspecialchars($_POST['dbname'] ?? $defaultDb) ?>"
               pattern="[a-zA-Z0-9_-]+" 
               placeholder="z.B. parfum_db">
        <div class="form-text">Wird automatisch erstellt, falls nicht vorhanden</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Benutzername</label>
        <input type="text" name="username" class="form-control" required 
               value="<?= htmlspecialchars($_POST['username'] ?? $defaultUser) ?>"
               placeholder="z.B. root">
    </div>
    <div class="mb-3">
        <label class="form-label">Passwort</label>
        <input type="password" name="password" class="form-control" 
               value="<?= htmlspecialchars($_POST['password'] ?? $defaultPass) ?>">
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <a href="?step=2" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Zurück
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-database me-2"></i>Verbindung testen & weiter
        </button>
    </div>
</form>
