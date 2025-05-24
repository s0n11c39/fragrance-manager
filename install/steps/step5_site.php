<?php
// Prüfen ob SQL importiert wurde
if (!isset($_SESSION['sql_imported']) || !$_SESSION['sql_imported']) {
    header('Location: ?step=4');
    exit;
}

// Standardwerte
$defaults = [
    'title' => 'Meine Duft-Website',
    'description' => 'Exklusive Düfte für Friseursalons',
    'language' => 'de',
    'timezone' => 'Europe/Berlin',
    'date_format' => 'd.m.Y',
    'time_format' => 'H:i',
    'currency' => 'EUR'
];

// Werte aus POST oder Standardwerte
$title = $_POST['title'] ?? $defaults['title'];
$description = $_POST['description'] ?? $defaults['description'];
$language = $_POST['language'] ?? $defaults['language'];
$timezone = $_POST['timezone'] ?? $defaults['timezone'];
$date_format = $_POST['date_format'] ?? $defaults['date_format'];
$time_format = $_POST['time_format'] ?? $defaults['time_format'];
$currency = $_POST['currency'] ?? $defaults['currency'];

$errors = [];

// Bei POST: validieren und speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validierung
    if (empty(trim($title))) {
        $errors[] = "Der Website-Titel darf nicht leer sein.";
    }
    
    if (strlen($description) > 160) {
        $errors[] = "Die Meta-Beschreibung sollte maximal 160 Zeichen lang sein.";
    }
    
    if (!in_array($language, ['de', 'en', 'fr'])) {
        $errors[] = "Ungültige Sprache ausgewählt.";
    }
    
    if (!in_array($timezone, DateTimeZone::listIdentifiers())) {
        $errors[] = "Ungültige Zeitzone ausgewählt.";
    }
    
    // Datum/Zeit-Format testen
    try {
        $date = new DateTime();
        $date->format($date_format);
        $date->format($time_format);
    } catch (Exception $e) {
        $errors[] = "Ungültiges Datum/Zeit-Format.";
    }
    
    if (!in_array($currency, ['EUR', 'USD', 'CHF', 'GBP'])) {
        $errors[] = "Ungültige Währung ausgewählt.";
    }
    
    if (empty($errors)) {
        $data = [
            'title' => trim($title),
            'description' => trim($description),
            'default_language' => $language,
            'timezone' => $timezone,
            'date_format' => $date_format,
            'time_format' => $time_format,
            'currency' => $currency,
            'installation_date' => date('Y-m-d H:i:s')
        ];

        // Verzeichnis erstellen falls nicht vorhanden
        if (!file_exists('../config')) {
            mkdir('../config', 0755, true);
        }

        $saved = file_put_contents(
            '../config/website_settings.json',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        
        if ($saved !== false) {
            $_SESSION['website'] = $data;
            header('Location: ?step=6');
            exit;
        } else {
            $errors[] = "Die Einstellungen konnten nicht gespeichert werden. Bitte prüfen Sie die Schreibrechte im config-Verzeichnis.";
        }
    }
}

// Verfügbare Zeitzonen für Europa
$timezones = array_filter(
    DateTimeZone::listIdentifiers(),
    function($tz) {
        return strpos($tz, 'Europe/') === 0;
    }
);

// Verfügbare Datumsformate
$dateFormats = [
    'd.m.Y' => date('d.m.Y'),
    'Y-m-d' => date('Y-m-d'),
    'd/m/Y' => date('d/m/Y'),
    'd.m.y' => date('d.m.y')
];

// Verfügbare Zeitformate
$timeFormats = [
    'H:i' => date('H:i'),
    'H:i:s' => date('H:i:s'),
    'h:i A' => date('h:i A')
];

?>

<h3>🌐 Website-Informationen</h3>
<p>Diese Angaben erscheinen in deinem Dashboard und in der öffentlichen Website.</p>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <h4>Bitte korrigiere folgende Fehler:</h4>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Website-Titel</label>
            <input type="text" name="title" class="form-control" required 
                   value="<?= htmlspecialchars($title) ?>"
                   placeholder="z.B. Meine Duft-Website">
            <div class="form-text">Der Haupttitel deiner Website</div>
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Sprache</label>
            <select name="language" class="form-select">
                <option value="de" <?= $language == 'de' ? 'selected' : '' ?>>Deutsch</option>
                <option value="en" <?= $language == 'en' ? 'selected' : '' ?>>Englisch</option>
                <option value="fr" <?= $language == 'fr' ? 'selected' : '' ?>>Französisch</option>
            </select>
            <div class="form-text">Die Standardsprache deiner Website</div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Meta-Beschreibung</label>
        <textarea name="description" class="form-control" rows="2" maxlength="160"
                  placeholder="Kurze Beschreibung deiner Website (max. 160 Zeichen)"><?= htmlspecialchars($description) ?></textarea>
        <div class="form-text">Diese Beschreibung wird in Suchergebnissen angezeigt</div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Zeitzone</label>
            <select name="timezone" class="form-select">
                <?php foreach ($timezones as $tz): ?>
                    <option value="<?= htmlspecialchars($tz) ?>" <?= $timezone == $tz ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tz) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Währung</label>
            <select name="currency" class="form-select">
                <option value="EUR" <?= $currency == 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                <option value="USD" <?= $currency == 'USD' ? 'selected' : '' ?>>US-Dollar ($)</option>
                <option value="CHF" <?= $currency == 'CHF' ? 'selected' : '' ?>>Schweizer Franken (CHF)</option>
                <option value="GBP" <?= $currency == 'GBP' ? 'selected' : '' ?>>Britisches Pfund (£)</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Datumsformat</label>
            <select name="date_format" class="form-select">
                <?php foreach ($dateFormats as $format => $example): ?>
                    <option value="<?= htmlspecialchars($format) ?>" <?= $date_format == $format ? 'selected' : '' ?>>
                        <?= htmlspecialchars($example) ?> (<?= htmlspecialchars($format) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Zeitformat</label>
            <select name="time_format" class="form-select">
                <?php foreach ($timeFormats as $format => $example): ?>
                    <option value="<?= htmlspecialchars($format) ?>" <?= $time_format == $format ? 'selected' : '' ?>>
                        <?= htmlspecialchars($example) ?> (<?= htmlspecialchars($format) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="?step=4" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Zurück
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Speichern & weiter
        </button>
    </div>
</form>

<script>
// Formularvalidierung aktivieren
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Zeichenzähler für Meta-Beschreibung
document.querySelector('textarea[name="description"]').addEventListener('input', function() {
    var remaining = 160 - this.value.length;
    this.nextElementSibling.textContent = 
        `Diese Beschreibung wird in Suchergebnissen angezeigt (noch ${remaining} Zeichen verfügbar)`;
});
</script>
