<?php
// admin/settings/settings.php
session_start();
require_once "../db.php";
require_once "../alerts.php";

require_once dirname(__DIR__, 2) . '/lang/lang.php';
if (!isset($lang)) {
   die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Zugriffsschutz
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", $lang['settings_no_permission']);
    header("Location: dashboard.php");
    exit;
}

// Pfad zur JSON-Datei
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';
$settings = [];

// Wenn die JSON-Datei existiert, laden wir die Einstellungen
if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

// Logo-Pfad aus den gespeicherten Einstellungen
$logoPath = isset($settings['site_logo']) ? '/' . $settings['site_logo'] : '/assets/img/logo2.svg'; // Default-Logo wenn nichts gespeichert wurde

// Meldungen abrufen
$stmt = $pdo->query("SELECT * FROM defects ORDER BY created_at DESC");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Einstellungen</title>
    <!-- CSS files -->
    <link href="../../assets/css/tabler.min.css" rel="stylesheet" />
    <link href="../../assets/css/demo.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/font-awesome.css">
    <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }
    </style>
</head>

<body>
    <script src="../../assets/js/demo-theme.min.js"></script>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <!-- BEGIN NAVBAR TOGGLER -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- END NAVBAR TOGGLER -->
                <!-- BEGIN NAVBAR LOGO -->
                <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                        <a href="#">
                            <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" style="height: 40px;">
                        </a>
                    </div>

                <!-- END NAVBAR LOGO -->
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" aria-label="Enable dark mode"
                                data-bs-original-title="Enable dark mode">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-1">
                                    <path
                                        d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                                    </path>
                                </svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" aria-label="Enable light mode"
                                data-bs-original-title="Enable light mode">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-1">
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                    <path
                                        d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                                    </path>
                                </svg>
                            </a>
                        </div>

                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url(./static/avatars/003m.jpg)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= htmlspecialchars($_SESSION["admin_name"] ?? "Admin") ?></div>
                                <div class="mt-1 small text-secondary">
                                    <?= htmlspecialchars(ucfirst($_SESSION["admin_role"] ?? "Nutzer")) ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Status</a>
                            <a href="./profile.html" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Feedback</a>
                            <div class="dropdown-divider"></div>
                            <a href="./settings.html" class="dropdown-item">Settings</a>
                            <a href="logout.php" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <!-- BEGIN NAVBAR MENU -->
                        <ul class="navbar-nav">
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/dashboard.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-gauge-high"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_dashboard'] ?> </span>
                                </a>
                            </li>
                            <!-- Users -->
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/users.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-users"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_users'] ?> </span>
                                </a>
                            </li>
                            <!-- Notes -->
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/notes.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-droplet"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_notes'] ?> </span>
                                </a>
                            </li>
                            <!-- Defekte -->
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/defects.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_defects'] ?> </span>
                                </a>
                            </li>
                        </ul>
                        <!-- END NAVBAR MENU -->
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
   <!-- Page header -->
   <div class="page-header d-print-none">
      <div class="container-xl">
         <div class="row g-2 align-items-center">
            <div class="col">
            <h2 class="page-title"><?= $lang['settings_page_title'] ?></h2>
            </div>
            <!-- Success & Error Messages -->
            <?php showAlerts(); ?>
         </div>
      </div>
   </div>
<!-- Page body -->
<div class="page-body">
   <div class="container-xl">
      <div class="card">
         <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-12 col-md-3 border-end">
               <div class="card-body">
                  <h4 class="subheader">Business settings</h4>
                  <div class="list-group list-group-transparent">
                  <a href="#" class="list-group-item list-group-item-action active" data-page="website_settings"><?= $lang['settings_tab_website'] ?></a>
                  <a href="#" class="list-group-item list-group-item-action" data-page="database_settings"><?= $lang['settings_tab_database'] ?></a>
                  <a href="#" class="list-group-item list-group-item-action" data-page="security_settings"><?= $lang['settings_tab_security'] ?></a>
                  <a href="#" class="list-group-item list-group-item-action" data-page="notification_settings"><?= $lang['settings_tab_notification'] ?></a>
                  <a href="#" class="list-group-item list-group-item-action" data-page="system_tools_settings"><?= $lang['settings_tab_system'] ?></a>
                  </div>
                  <h4 class="subheader mt-4">Experience</h4>
                  <div class="list-group list-group-transparent">
                     <a href="#" class="list-group-item list-group-item-action" data-page="feedback">Give Feedback</a>
                  </div>
               </div>
            </div>

            <!-- Content Container -->
            <div class="col-12 col-md-9 d-flex flex-column">
               <div id="settings-content" class="card-body">
                  <!-- Inhalte werden hier per AJAX geladen -->
               </div>
               <div class="card-footer bg-transparent mt-auto">
                  <div class="btn-list justify-content-end">
                     <a href="#" class="btn btn-1"><?= $lang['settings_button_cancel'] ?></a>
                     <button type="submit" class="btn btn-primary btn-2" id="submit-settings-button"><?= $lang['settings_button_save'] ?></button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- Footer -->
<footer class="footer footer-transparent d-print-none">
   <div class="container-xl">
      <div class="row text-center align-items-center flex-row-reverse">
         <div class="col-lg-auto ms-lg-auto">
         </div>
         <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <ul class="list-inline list-inline-dots mb-0">
               <li class="list-inline-item">
                  Copyright &copy; 2025
                  <a href="." class="link-secondary">L'CREE</a>.
                  All rights reserved.
               </li>
               <li class="list-inline-item">
                  <a href="./changelog.html" class="link-secondary" rel="noopener">
                  v1.0.0-beta20
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </div>
</footer>
</div>
</div>
<!-- Duftnote löschen -->
<div class="modal modal-blur fade" id="modal-delete-note" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         <div class="modal-status bg-danger"></div>
         <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
               stroke-linejoin="round">
               <path d="M12 9v4"></path>
               <path
                  d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
               </path>
               <path d="M12 16h.01"></path>
            </svg>
            <h3>Bist du sicher?</h3>
            <div class="text-secondary">
               Möchtest du die Duftnote<br>
               <strong id="delete-note-name"></strong><br>
               wirklich löschen?
            </div>
         </div>
         <div class="modal-footer">
            <div class="w-100">
               <div class="row">
                  <div class="col">
                     <a href="#" class="btn w-100" data-bs-dismiss="modal">Abbrechen</a>
                  </div>
                  <div class="col">
                     <form method="post" action="delete_note.php" id="confirm-delete-note-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
                        <input type="hidden" name="id" id="delete-note-id">
                        <button type="submit" class="btn btn-danger w-100">Löschen</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- Tabler Core -->
<script src="../../assets/js/tabler.min.js" defer></script>
<script src="../../assets/js/demo.min.js" defer></script>
<script src="../../assets/js/jquery-3.6.0.min.js"></script>
<!-- Success & Error Messages -->
<script>
   setTimeout(function() {
       document.querySelectorAll('.alert-dismissible').forEach(function(alertBox) {
           alertBox.classList.remove('show');
       });
   }, 4000); // Nach 4 Sekunden ausblenden
</script>

<!--  -->
<script>
$(document).ready(function () {
   function loadContent(page) {
      $('#settings-content').load(`${page}.php`, function(response, status, xhr) {
         if (status === "error") {
            $('#settings-content').html(`<div class='alert alert-danger'><?= $lang['settings_load_error'] ?> "${page}.php": ${xhr.status} - ${xhr.statusText}<br><?= $lang['settings_load_error_details'] ?></div>`);
         }
      });

      // Sidebar aktiv setzen
      $('.list-group-item').removeClass('active');
      $(`.list-group-item[data-page="${page}"]`).addClass('active');
   }

   // ➤ 1. URL-Parameter auslesen
   const urlParams = new URLSearchParams(window.location.search);
   const defaultPage = 'website_settings';
   const tab = urlParams.get('tab') || defaultPage;

   // ➤ 2. Relevante Seite laden
   loadContent(tab);

   // ➤ 3. Bei Klick auf Tab
   $('.list-group-item[data-page]').on('click', function (e) {
      e.preventDefault();
      const page = $(this).data('page');
      history.replaceState(null, '', `?tab=${page}`);
      loadContent(page);
   });
});

</script>

<script>
document.getElementById('submit-settings-button').addEventListener('click', function () {
    const form = document.getElementById('active-settings-form');
    if (!form) {
        alert("Kein Formular vorhanden.");
        return;
    }

    // Aktiven Tab ermitteln (der per data-page="..." gekennzeichnet ist)
    const activeTab = document.querySelector('.list-group-item.active')?.getAttribute('data-page');

    // Vorhandenes action-Input entfernen (um Duplikate zu vermeiden)
    const existingAction = form.querySelector('input[name="action"]');
    if (existingAction) {
        existingAction.remove();
    }

    // Action-Wert je nach Tab setzen
    let actionValue = '';

    switch (activeTab) {
        case 'database_settings':
            actionValue = 'save_database_settings';
            break;
        case 'website_settings':
            actionValue = 'save_website_settings';
            break;
        case 'security_settings':
            actionValue = 'save_security_settings';
            break;
        case 'notification_settings':
            actionValue = 'save_notification_settings';
            break;
        case 'system_tools_settings':
            actionValue = 'save_system_settings';
            break;
        // ggf. weitere cases
    }

    if (actionValue) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = actionValue;
        form.appendChild(hiddenInput);
    }

    form.submit();
});
</script>


<!-- Datenbank Verbindung prüfen -->
<script>
$(document).on('click', '#test-db-connection', function () {
   const form = $('#active-settings-form');

   const data = {
      db_host: form.find('[name="db_host"]').val(),
      db_name: form.find('[name="db_name"]').val(),
      db_user: form.find('[name="db_user"]').val(),
      db_pass: form.find('[name="db_pass"]').val()
   };

   $('#db-connection-result').html('<div class="text-muted">' + langTestConnection.testing + '</div>');

   $.post('test_db_connection.php', data, function (response) {
      $('#db-connection-result').html(response);
   }).fail(function () {
    $('#db-connection-result').html('<div class="text-danger">' + langTestConnection.error + '</div>');
   });
});
</script>


<!-- Logo-Vorschau Script -->
<script>
document.getElementById('site_logo_input').addEventListener('change', function (e) {
   const file = e.target.files[0];
   if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
         document.getElementById('logo_preview').src = event.target.result;
      };
      reader.readAsDataURL(file);
   }
});
</script>

</body>
</html>