<?php
// Anforderungen prüfen
$checks = [
    'PHP-Version ≥ 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'PDO verfügbar' => extension_loaded('pdo'),
    'Session-Unterstützung' => function_exists('session_start'),
];

// Ordnerrechte prüfen
$paths = [
    '../config' => ['writable' => true],
    '../install/install.sql' => ['readable' => true]
];

$permissionResults = [];

foreach ($paths as $path => $req) {
    $result = [
        'exists' => file_exists($path),
        'writable' => isset($req['writable']) ? is_writable($path) : null,
        'readable' => isset($req['readable']) ? is_readable($path) : null
    ];
    $permissionResults[$path] = $result;
}

// Wenn alles passt, weiter erlauben
$canProceed = !in_array(false, $checks) && !in_array(false, array_column($permissionResults, 'exists')) &&
    !in_array(false, array_column($permissionResults, 'writable')) &&
    !in_array(false, array_column($permissionResults, 'readable'));
?>

<h3>🛠 Systemprüfung</h3>
<p>Hier prüfen wir, ob dein Server alle Anforderungen erfüllt und wichtige Verzeichnisse zugänglich sind.</p>

<div class="mb-4">
    <h4 class="mb-2">Allgemeine Anforderungen</h4>
    <ul class="list-group">
        <?php foreach ($checks as $label => $ok): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $label ?>
                <span class="badge <?= $ok ? 'bg-green' : 'bg-red' ?>">
                    <?= $ok ? 'OK' : 'Fehlt' ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="mb-4">
    <h4 class="mb-2">Datei- & Ordnerrechte</h4>
    <ul class="list-group">
        <?php foreach ($permissionResults as $path => $res): ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between">
                    <strong><?= $path ?></strong>
                    <div>
                        <?= !$res['exists'] ? '<span class="badge bg-red">Fehlt</span>' : '' ?>
                        <?= $res['readable'] !== null ? ($res['readable'] ? '<span class="badge bg-green">Lesbar</span>' : '<span class="badge bg-red">Nicht lesbar</span>') : '' ?>
                        <?= $res['writable'] !== null ? ($res['writable'] ? '<span class="badge bg-green">Schreibbar</span>' : '<span class="badge bg-red">Nicht schreibbar</span>') : '' ?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($canProceed): ?>
    <div class="text-end">
        <a href="?step=2" class="btn btn-primary">Weiter</a>
    </div>
<?php else: ?>
    <div class="alert alert-danger mt-4">
        Bitte behebe die oben stehenden Probleme, bevor du fortfährst.
    </div>
<?php endif; ?>
