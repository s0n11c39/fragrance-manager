<?php
// Automatische Erkennung
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME'])); // eine Ebene zurück von /install/
$baseUrl = rtrim($protocol . $host . $scriptDir, '/');
$rootPath = realpath(dirname(__DIR__, 2)); // echtes Root-Verzeichnis

// Bei POST speichern wir config/path_config.json
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseUrl = rtrim($_POST['base_url'], '/');
    $rootPath = rtrim($_POST['root_path'], '/');

    $pathData = [
        'base_url' => $baseUrl,
        'root_path' => $rootPath
    ];

    if (!is_dir('../config')) {
        mkdir('../config', 0755, true);
    }

    $saved = file_put_contents('../config/path_config.json', json_encode($pathData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    if ($saved !== false) {
        $_SESSION['base_url'] = $baseUrl;
        $_SESSION['root_path'] = $rootPath;
        header('Location: ?step=3');
        exit;
    } else {
        $error = "Pfadkonfiguration konnte nicht gespeichert werden.";
    }
}
?>

<h3>📂 Pfade & URL</h3>
<p>Bitte überprüfe, ob die automatisch erkannten Pfade stimmen. Du kannst sie ggf. anpassen.</p>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Basis-URL</label>
        <input type="text" name="base_url" class="form-control" required value="<?= htmlspecialchars($baseUrl) ?>">
        <small class="form-hint">Beispiel: http://localhost/parfum</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Root-Verzeichnis (Dateisystempfad)</label>
        <input type="text" name="root_path" class="form-control" required value="<?= htmlspecialchars($rootPath) ?>">
        <small class="form-hint">Beispiel: C:\xampp\htdocs\parfum</small>
    </div>

    <div class="text-end">
        <a href="?step=1" class="btn btn-secondary">Zurück</a>
        <button type="submit" class="btn btn-primary">Weiter</button>
    </div>
</form>
