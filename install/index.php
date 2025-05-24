<?php
if (file_exists(__DIR__ . '/../config/installed.lock')) {
    echo '<div class="alert alert-danger" style="margin:2em">Die Installation wurde bereits abgeschlossen.<br>Bitte entferne die Datei <code>config/installed.lock</code>, um den Installer erneut zu starten.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Parfum Installer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css'>
<link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css'><link rel="stylesheet" href="./style.css">

<link rel="stylesheet" href="assets/css/font-awesome.css">
  <link href="../../assets/css/tabler.min.css" rel="stylesheet" />
  <link href="../../assets/css/demo.min.css" rel="stylesheet" />


  <!-- Font Awesome PRO -->
  <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css"
      >

      <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-solid.css"
      >

      <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-regular.css"
      >

      <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/sharp-light.css"
      >
      <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/duotone.css"
      />
      <link
        rel="stylesheet"
        href="https://site-assets.fontawesome.com/releases/v6.7.2/css/brands.css"
      />
</head>
<body>


<div class="page-wrapper">
        <!-- BEGIN PAGE HEADER -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">Installer</h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
                  <!-- Download SVG icon from http://tabler.io/icons/icon/printer -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path>
                  </svg>
                  Print Invoice
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE HEADER -->
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
          <div class="container-xl">
            <div class="card card-lg">
              <div class="card-body">

   <!-- Multi step form --> 
<section class="multi_step_form">  
  <form id="msform"> 
    <!-- Tittle -->
    <div class="tittle tittle text-center">
      <h2>Parfum Installer</h2>
      <p>Bitte führe die einzelnen Schritte aus, um die Installation abzuschließen.</p>
    </div>
    <!-- progressbar -->
    <ul id="progressbar">
  <li class="active"><i class="fas fa-microchip"></i><br>Systemprüfung</li>
  <li><i class="fas fa-folder-open"></i><br>Pfade</li>
  <li><i class="fas fa-database"></i><br>Datenbank</li>
  <li><i class="fas fa-globe"></i><br>Website-Infos</li>
  <li><i class="fas fa-user-shield"></i><br>Admin-Nutzer</li>
  <li><i class="fas fa-circle-check"></i><br>Fertig</li>
</ul>


    <!-- Step 1 -->
    <fieldset>
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
function checkPasses($array) {
  return !in_array(false, array_filter($array, function($v) {
      return $v !== null;
  }));
}

$canProceed =
  checkPasses($checks) &&
  checkPasses(array_column($permissionResults, 'exists')) &&
  checkPasses(array_column($permissionResults, 'writable')) &&
  checkPasses(array_column($permissionResults, 'readable'));

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

<!-- Weiter-Button -->
<button type="button" class="next btn btn-primary step1-next" style="<?= $canProceed ? '' : 'display: none;' ?>">
  Weiter
</button>



<script>
  document.addEventListener("DOMContentLoaded", function () {
    const step1Button = document.querySelector(".step1-next");

    <?php if (!$canProceed): ?>
      if (step1Button) {
        step1Button.addEventListener("click", function (e) {
          e.preventDefault();
          alert("Bitte behebe die Systemprobleme, bevor du fortfahren kannst.");
        });
      }
    <?php endif; ?>

    // Zeige den Button wieder an, wenn der Benutzer zu Schritt 1 zurückgeht
    $(".previous").click(function () {
      const prev_fs = $(this).closest("fieldset").prev("fieldset");
      const index = $("fieldset").index(prev_fs);

      // Falls zurück zu Schritt 1 (Index 0)
      if (index === 0) {
        const step1Btn = document.querySelector(".step1-next");
        if (step1Btn && step1Btn.style.display === "none") {
          // Wenn die Systemprüfung bestanden wurde → wieder anzeigen
          <?php if ($canProceed): ?>
            step1Btn.style.display = "inline-block";
          <?php endif; ?>
        }
      }
    });
  });
</script>


<?php if (!$canProceed): ?>
  <small class="text-danger d-block mt-2">
    Bitte behebe die Systemprobleme, bevor du fortfahren kannst.
  </small>
<?php endif; ?>

    </fieldset>

<!-- Step 2: Pfade -->
<fieldset>
<?php
// Automatische Erkennung
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME'])); // z. B. /parfum
$baseUrl = rtrim($protocol . $host . $scriptDir, '/');
$rootPath = realpath(dirname(__DIR__, 2));

// Bestehende Session-Werte verwenden, falls vorhanden
$baseUrl = $_SESSION['base_url'] ?? $baseUrl;
$rootPath = $_SESSION['root_path'] ?? $rootPath;
?>

<h3>📂 Pfade & URL</h3>
<p>Bitte überprüfe, ob die automatisch erkannten Pfade stimmen. Du kannst sie ggf. anpassen.</p>

<div class="mb-3">
  <label class="form-label">Basis-URL</label>
  <input type="text" name="base_url" id="base_url" class="form-control" required value="<?= htmlspecialchars($baseUrl) ?>">
  <small class="form-hint">Beispiel: http://localhost/parfum</small>
</div>

<div class="mb-3">
  <label class="form-label">Root-Verzeichnis (Dateisystempfad)</label>
  <input type="text" name="root_path" id="root_path" class="form-control" required value="<?= htmlspecialchars($rootPath) ?>">
  <small class="form-hint">Beispiel: C:\xampp\htdocs\parfum</small>
</div>

<div class="d-flex justify-content-between">
  <button type="button" class="previous btn btn-secondary">Zurück</button>
  <!-- Benutzer klickt diesen Button -->
<button type="button" class="btn btn-primary" id="path-check-button">Weiter</button>
<!-- Dieser wird per JS ausgelöst -->
<button type="button" class="next btn btn-primary d-none">Versteckter Weiter</button>
</div>

<script>
  document.getElementById('path-check-button').addEventListener('click', function (e) {
    e.preventDefault();

    const baseUrl = document.getElementById('base_url').value;
    const rootPath = document.getElementById('root_path').value;

    fetch('save_paths.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ base_url: baseUrl, root_path: rootPath })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        $(this).closest("fieldset").find(".next").trigger("click");
      } else {
        alert("Fehler beim Speichern:\n" + data.message);
      }
    })
    .catch(err => {
      alert("Fehler beim Speichern:\n" + err);
    });
  });
</script>
</fieldset>


<!-- Step 3: Datenbankverbindung -->
<fieldset>
<?php
$defaultHost = 'localhost';
$defaultDb = '';
$defaultUser = '';
$defaultPass = '';
?>

<h3>🗄️ Datenbankverbindung</h3>
<p>Gib die Zugangsdaten für deine MySQL-Datenbank ein. Wir testen die Verbindung vor dem Speichern.</p>

<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Host</label>
    <input type="text" name="host" id="db_host" class="form-control" required value="<?= htmlspecialchars($defaultHost) ?>">
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">Datenbankname</label>
    <input type="text" name="dbname" id="db_name" class="form-control" required value="<?= htmlspecialchars($defaultDb) ?>">
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Benutzername</label>
    <input type="text" name="username" id="db_user" class="form-control" required value="<?= htmlspecialchars($defaultUser) ?>">
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">Passwort</label>
    <input type="password" name="password" id="db_pass" class="form-control" value="<?= htmlspecialchars($defaultPass) ?>">
  </div>
</div>


<div class="d-flex justify-content-between align-items-center">
  <button type="button" class="previous btn btn-secondary">Zurück</button>
  <button type="button" class="btn btn-primary" id="db-check-button">Verbindung testen & weiter</button>
</div>

<!-- Unsichtbarer Weiter-Button außerhalb des sichtbaren Layouts -->
<button type="button" class="next btn btn-primary d-none">Versteckter Weiter</button>


<script>
  document.getElementById('db-check-button').addEventListener('click', function (e) {
    e.preventDefault();

    const host = document.getElementById('db_host').value;
    const dbname = document.getElementById('db_name').value;
    const username = document.getElementById('db_user').value;
    const password = document.getElementById('db_pass').value;

    fetch('check_db_connection.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ host, dbname, username, password })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        $(this).closest("fieldset").find(".next").trigger("click");
      } else {
        alert("Verbindung fehlgeschlagen:\n" + data.message);
      }
    })
    .catch(err => {
      alert("Fehler beim Verbindungsversuch:\n" + err);
    });
  });
</script>
</fieldset>


<!-- Step 4: Website-Infos -->
<fieldset>
  <h3>🌐 Website-Informationen</h3>
  <p>Diese Angaben erscheinen in deinem Dashboard und auf der öffentlichen Website.</p>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Website-Titel</label>
      <input type="text" name="site_title" id="site_title" class="form-control" required value="Meine Duft-Website">
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Meta-Beschreibung</label>
      <input type="text" name="site_description" id="site_description" class="form-control" value="Exklusive Düfte für Friseursalons">
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Sprache</label>
      <select name="site_language" id="site_language" class="form-select">
        <option value="de" selected>Deutsch</option>
        <option value="en">Englisch</option>
        <option value="fr">Französisch</option>
      </select>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    <button type="button" class="previous btn btn-secondary">Zurück</button>
    <button type="button" class="btn btn-primary" id="site-save-button">Speichern & Weiter</button>
  </div>

  <!-- Unsichtbarer Weiter-Button -->
  <button type="button" class="next btn btn-primary d-none">Versteckter Weiter</button>

  <script>
    document.getElementById('site-save-button').addEventListener('click', function (e) {
      e.preventDefault();

      const title = document.getElementById('site_title').value;
      const description = document.getElementById('site_description').value;
      const language = document.getElementById('site_language').value;

      fetch('save_website_info.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, description, language })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          $(this).closest("fieldset").find(".next").trigger("click");
        } else {
          alert("Fehler beim Speichern:\n" + data.message);
        }
      })
      .catch(err => {
        alert("Fehler beim Speichern:\n" + err);
      });
    });
  </script>
</fieldset>


<!-- Step 5: Admin-Benutzer -->
<fieldset>
  <h3>👤 Admin-Benutzer erstellen</h3>
  <p>Lege jetzt dein Administrator-Konto an, mit dem du dich später einloggen kannst.</p>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Benutzername</label>
      <input type="text" id="admin_username" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">E-Mail-Adresse</label>
      <input type="email" id="admin_email" class="form-control" required>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Passwort</label>
      <input type="password" id="admin_password" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Passwort wiederholen</label>
      <input type="password" id="admin_password2" class="form-control" required>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    <button type="button" class="previous btn btn-secondary">Zurück</button>
    <button type="button" class="btn btn-primary" id="create-admin-user">Konto erstellen & abschließen</button>
  </div>

  <!-- Unsichtbarer Weiter-Button -->
  <button type="button" class="next btn btn-primary d-none">Versteckter Weiter</button>

  <script>
    document.getElementById('create-admin-user').addEventListener('click', function (e) {
      e.preventDefault();

      const username = document.getElementById('admin_username').value.trim();
      const email = document.getElementById('admin_email').value.trim();
      const password = document.getElementById('admin_password').value;
      const password2 = document.getElementById('admin_password2').value;

      if (!username || !email || !password || !password2) {
        alert("Bitte fülle alle Felder aus.");
        return;
      }

      if (password !== password2) {
        alert("Die Passwörter stimmen nicht überein.");
        return;
      }

      fetch('save_admin_user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email, password, password2 })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          $(this).closest("fieldset").find(".next").trigger("click");
        } else {
          alert("Fehler: " + data.message);
        }
      })
      .catch(err => {
        alert("Fehler beim Speichern:\n" + err);
      });
    });
  </script>
</fieldset>


    <!-- Step 6 -->
    <fieldset>
                <div class="card-body text-center">
                  <div class="icon-check">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2fb344" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" fill="#2fb344" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM9.293 12.293l-2.293-2.293-1.414 1.414 3.707 3.707 7.707-7.707-1.414-1.414z" />
                    </svg>
                  </div>
                  <h2 class="card-title mb-2">Installation erfolgreich</h2>
                  <p class="text-muted mb-4">
                    Deine Parfum-Website wurde erfolgreich eingerichtet. <br>
                    Du kannst dich jetzt mit deinem Admin-Zugang anmelden.
                  </p>
                  <a href="../admin/index.php" class="btn btn-success w-100">
                    Zum Admin-Dashboard
                  </a>
                </div>
                <div class="card-footer text-center text-muted small">
                  Zur Sicherheit empfehlen wir, den Ordner <code>/install/</code> zu löschen oder umzubenennen.
                </div>

    </fieldset>
  </form>  
</section> 
<!-- End Multi step form -->



              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE BODY -->
        <!--  BEGIN FOOTER  -->
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                  <li class="list-inline-item"><a href="./license.html" class="link-secondary">License</a></li>
                  <li class="list-inline-item">
                    <a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary" rel="noopener">Source code</a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary" rel="noopener">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-pink icon-inline icon-4">
                        <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                      </svg>
                      Sponsor
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright © 2025
                    <a href="." class="link-secondary">Tabler</a>. All rights reserved.
                  </li>
                  <li class="list-inline-item">Generated 2025-04-16 17:23 +0000</li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
        <!--  END FOOTER  -->
      </div>


      
  
    


<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script><script  src="./script.js"></script>
</body>
</html>
