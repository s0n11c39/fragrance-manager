<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

require_once "db.php";
require_once "alerts.php";

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Alle Düfte
$scents = $pdo->query("SELECT * FROM scents ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Zähler vorbereiten
$total     = $pdo->query("SELECT COUNT(*) FROM scents")->fetchColumn();
$count_m   = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Herren'")->fetchColumn();
$count_w   = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Damen'")->fetchColumn();
$count_u   = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Unisex'")->fetchColumn();

// Pfad zur JSON-Datei
$configPath = dirname(__DIR__, 2) . '/config/website_settings.json';
$settings = [];

// Wenn die JSON-Datei existiert, laden wir die Einstellungen
if (file_exists($configPath)) {
    $settings = json_decode(file_get_contents($configPath), true);
}

// Logo-Pfad aus den gespeicherten Einstellungen
$logoPath = isset($settings['site_logo']) ? '/' . $settings['site_logo'] : '/assets/img/logo2.svg'; // Default-Logo wenn nichts gespeichert wurde

?>

<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard</title>
    <!-- CSS files -->
    <link href="../assets/css/tabler.min.css" rel="stylesheet" />
    <link href="../assets/css/demo.min.css" rel="stylesheet" />
    <link href="../assets/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }
    </style>
    <style>
    /* Dropdown-Hintergrund fixen */
    .ts-dropdown {
        background-color: #fff !important;
        opacity: 1 !important;
        backdrop-filter: none !important;
    }

    /* Einzelne Optionen */
    .ts-dropdown .option {
        opacity: 1 !important;
        background-color: #fff !important;
        color: #000 !important;
    }
</style>
</head>

<body>
    <script src="../assets/js/demo-theme.min.js"></script>
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
                        <a href=".">
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
                            <a href="settings/settings.php" class="dropdown-item"><?= $lang['nav_settings'] ?></a>
                            <a href="logout.php" class="dropdown-item"><?= $lang['nav_logout'] ?></a>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <!-- BEGIN NAVBAR MENU -->
                        <ul class="navbar-nav">
                            <!-- Dashboard -->
                            <li class="nav-item active">
                                <a class="nav-link" href="dashboard.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-gauge-high"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_dashboard'] ?> </span>
                                </a>
                            </li>
                            <!-- Users -->
                            <li class="nav-item">
                                <a class="nav-link" href="users.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-users"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_users'] ?> </span>
                                </a>
                            </li>
                            <!-- Notes -->
                            <li class="nav-item">
                                <a class="nav-link" href="notes.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-droplet"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_notes'] ?> </span>
                                </a>
                            </li>
                            <!-- Defekte -->
                            <li class="nav-item">
                                <a class="nav-link" href="defects.php">
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
                            <!-- Page pre-title -->
                            <div class="page-pretitle">
                            <?= $lang['dashboard_pretitle'] ?>  
                            </div>
                            <h2 class="page-title">
                            <?= $lang['dashboard_title'] ?> 
                            </h2>
                        </div>
                        <!-- Button 'Neuer Duft' -->
                    <?php if (in_array($_SESSION["admin_role"], ["admin", "editor"])): ?>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal-add-scent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    <?= $lang['dashboard_button_new_scent'] ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">

                        <!-- Cards Duefte -->
                        <div class="col-12">
                            <div class="row row-cards">
                                <!-- Gesamt Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-primary text-white avatar">
                                                        <i class="fa-solid fa-boxes-stacked"
                                                            style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $total ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['dashboard_total_label'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Herren Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-cyan text-white avatar">
                                                        <i class="fa-solid fa-mars" style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_m ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['dashboard_men_label'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Damen Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-pink text-white avatar">
                                                        <i class="fa-solid fa-venus" style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_w ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['dashboard_women_label'] ?> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unisex Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-teal text-white avatar">
                                                        <i class="fa-solid fa-venus-mars fa-2x"
                                                            style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_u ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['dashboard_unisex_label'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card card-md">
                                <div class="card-stamp card-stamp-lg">
                                    <div class="card-stamp-icon bg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M5 11a7 7 0 0 1 14 0v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-7" />
                                            <path d="M10 10l.01 0" />
                                            <path d="M14 10l.01 0" />
                                            <path d="M10 14a3.5 3.5 0 0 0 4 0" />
                                        </svg>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Success & Error Messages -->
                        <?php showAlerts(); ?>

                        <!-- Table 'Düfte' -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?= $lang['dashboard_table_title'] ?></h3>
                                </div>
                                <div class="card-body border-bottom py-3">
                                    <div class="d-flex">
                                        <div class="text-secondary">
                                        <?= $lang['pagination_showing'] ?>
                                            <div class="mx-2 d-inline-block">
                                                <input type="text" id="entriesCount"
                                                    class="form-control form-control-sm" value="10" size="3" min="1"
                                                    aria-label="Einträge anzeigen">
                                            </div>
                                            <?= $lang['dashboard_table_entries'] ?>
                                        </div>
                                        <div class="ms-auto text-secondary">
                                        <?= $lang['placeholder_search'] ?>
                                            <div class="ms-2 d-inline-block">
                                                <input type="text" id="searchInput" class="form-control form-control-sm"
                                                    aria-label="Suche">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th class="w-1"><input class="form-check-input m-0 align-middle"
                                                        type="checkbox" aria-label="Select all invoices"></th>
                                                <th class="w-1"><?= $lang['dashboard_table_column_no'] ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-sm icon-thick" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 15l6 -6l6 6" />
                                                    </svg>
                                                </th>
                                                <th><?= $lang['dashboard_table_column_code'] ?></th>
                                                <th><?= $lang['dashboard_table_column_inspired_by'] ?></th>
                                                <th><?= $lang['dashboard_table_column_gender'] ?></th>
                                                <th><?= $lang['dashboard_table_column_direction'] ?></th>
                                                <th><?= $lang['dashboard_table_column_qrcode'] ?></th>
                                                <th><?= $lang['dashboard_table_column_actions'] ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="scentsTableBody">
                                            <?php $i = 1; foreach ($scents as $scent): ?>
                                            <tr>
                                                <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                        aria-label="Select invoice"></td>
                                                <td><span class="text-secondary"><?= $i++ ?></span></td>
                                                <td><?= htmlspecialchars($scent["code"]) ?></td>
                                                <td><?= htmlspecialchars($scent["inspired_by"]) ?></td>
                                                <td><?= htmlspecialchars($scent["gender"]) ?></td>
                                                <td><?= htmlspecialchars($scent["direction"]) ?></td>
                                                <td><img src="<?= htmlspecialchars($scent['qr_code']) ?>" alt="QR Code" style="width: 50px;"></td>

                                                <td class="text-end">

                                                                                                <?php if (empty($scent['qr_code'])): ?>
                                                    <form action="generate_qrcode.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $scent['id'] ?>">
                                                        <button type="submit" class="btn btn-link text-success p-0" title="<?= $lang['dashboard_generate_qrcode'] ?> ">
                                                            <i class="fa-solid fa-qrcode"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>


                                                    <?php if (in_array($_SESSION["admin_role"], ["admin", "editor"])): ?>
                                                    <!-- Bearbeiten -->
                                                    <a href="#"
                                                        onclick="openEditModal(<?= $scent['id'] ?>); return false;"
                                                        class="text-primary me-2" title="<?= $lang['btn_edit'] ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <!-- Löschen -->
                                                    <button type="button" class="btn btn-link p-0 m-0 text-danger"
                                                        title="<?= $lang['btn_delete'] ?> " style="text-decoration: none;"
                                                        onclick="confirmDelete(<?= $scent['id'] ?>, '<?= htmlspecialchars($scent['code']) ?>', '<?= htmlspecialchars($scent['name']) ?>')">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <!-- Pagination -->
                            <div class="card-footer d-flex align-items-center">
                                    <p class="m-0 text-secondary" id="pagination-info"><?= $lang['pagination_showing'] ?><span>1</span><?= $lang['pagination_to'] ?>
                                        <span>8</span> <?= $lang['pagination_of'] ?>  <span>16</span> <?= $lang['pagination_entries'] ?>
                                    </p>
                                    <ul class="pagination m-0 ms-auto" id="pagination">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 6l-6 6l6 6" />
                                                </svg>
                                                <?= $lang['pagination_prev'] ?>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                            <?= $lang['pagination_next'] ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 6l6 6l-6 6" />
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
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
    <!-- Modalbox Duft Hinzufügen -->
<?php if (in_array($_SESSION["admin_role"], ["admin", "editor"])): ?>
    <div class="modal modal-blur fade" id="modal-add-scent" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="save_scent.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $lang['dashboard_modal_add_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">

                    <!-- Code (optional) -->
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['dashboard_modal_add_code'] ?> </label>
                        <input type="text" name="code" class="form-control" placeholder="z. B. M-042">
                    </div>
                    <!-- Original Duftname -->
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['dashboard_modal_add_inspired_by'] ?></label>
                        <input type="text" name="inspired_by" class="form-control" required>
                    </div>

                    <!-- Geschlecht -->
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['dashboard_modal_add_gender'] ?></label>
                        <select name="gender" class="form-select" required>
                            <option value="Herren">Herren</option>
                            <option value="Damen">Damen</option>
                            <option value="Unisex">Unisex</option>
                        </select>
                    </div>

                    <!-- Duftrichtung -->
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['dashboard_modal_add_description'] ?></label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="z.B. Süß, Würzig, Holzig"></textarea>
                    </div>

                    <!-- Umschalter für Duftpyramide -->
                    <div class="mb-3">
                    <label class="form-check form-switch form-switch-2">
                        <input class="form-check-input" type="checkbox" name="use_pyramid" id="usePyramidSwitch" checked>
                        <span class="form-check-label"><?= $lang['dashboard_modal_add_pyramid_switch'] ?></span>
                    </label>
                    </div>

                    <!-- Duftpyramide -->
                    <div id="pyramidFields">
                    <h4><?= $lang['dashboard_modal_scent_pyramid'] ?></h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><?= $lang['dashboard_modal_add_top_notes'] ?></label>
                            <select name="notes_top[]" id="notes_top" class="form-select" multiple></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><?= $lang['dashboard_modal_add_heart_notes'] ?></label>
                            <select name="notes_heart[]" id="notes_heart" class="form-select" multiple></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><?= $lang['dashboard_modal_add_base_notes'] ?></label>
                            <select name="notes_base[]" id="notes_base" class="form-select" multiple></select>
                        </div>
                    </div>
                    </div>

                    <!-- Alternativ: Allgemeine Noten (vorerst versteckt) -->
                    <div id="generalNotesBlock" style="display: none;">
                    <h4><?= $lang['dashboard_modal_add_general_notes_title'] ?></h4>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['dashboard_modal_add_general_notes_label'] ?></label>
                        <select name="notes_all[]" id="notes_all" class="form-select" multiple></select>
                    </div>
                    </div>

                    <!-- Diagramme -->
                    <h4><?= $lang['dashboard_modal_add_attributes_title'] ?></h4>
                    <div class="row">

                        <!-- Dufttyp -->
                        <div class="mb-3">
                            <label class="form-label"><?= $lang['dashboard_modal_add_type'] ?></label>
                            <div id="dufttyp-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="dufttyp_label[]" class="form-control"
                                        placeholder="z. B. Süß">
                                    <input type="number" name="dufttyp_value[]" class="form-control" placeholder="40"
                                        min="0" max="100">
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="this.parentElement.remove()">✕</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="addAttributeRow('dufttyp')"><?= $lang['dashboard_modal_add_type_add'] ?></button>
                        </div>

                        <!-- Stil -->
                        <label class="form-label"><?= $lang['dashboard_modal_add_style'] ?></label>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_men'] ?> </span>
                                    <input type="number" class="form-control" name="stil_Herren" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_women'] ?> </span>
                                    <input type="number" class="form-control" name="stil_Damen" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_classic'] ?> </span>
                                    <input type="number" class="form-control" name="stil_Klassisch"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_modern'] ?> </span>
                                    <input type="number" class="form-control" name="stil_Modern" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                        </div>


                        <!-- Jahreszeit -->
                        <label class="form-label"><?= $lang['dashboard_modal_add_season'] ?> </label>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_spring'] ?> </span>
                                    <input type="number" class="form-control" name="season_Fruehling"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_summer'] ?> </span>
                                    <input type="number" class="form-control" name="season_Sommer" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_autumn'] ?> </span>
                                    <input type="number" class="form-control" name="season_Herbst" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_winter'] ?> </span>
                                    <input type="number" class="form-control" name="season_Winter" placeholder="subdomain"
                                        autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                        </div>



                        <!-- Anlass -->
                        <label class="form-label"><?= $lang['dashboard_modal_add_occasion'] ?>    </label>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_daily'] ?> </span>
                                    <input type="number" class="form-control" name="occasion_Taeglich"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_leisure'] ?> </span>
                                    <input type="number" class="form-control" name="occasion_Freizeit"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_going_out'] ?> </span>
                                    <input type="number" class="form-control" name="occasion_Ausgehen"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_work'] ?> </span>
                                    <input type="number" class="form-control" name="occasion_Arbeit"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_evening'] ?> </span>
                                    <input type="number" class="form-control" name="occasion_Abend"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"> <?= $lang['label_sport'] ?>  </span>
                                    <input type="number" class="form-control" name="occasion_Sport"
                                        placeholder="subdomain" autocomplete="off">
                                    <span class="input-group-text">% </span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            <?= $lang['btn_save'] ?>
                        </button>
                    </div>
            </form>
        </div>
    </div>
    </div>
<?php endif; ?>

<!-- Modablbox Duft Bearbeiten -->
<div class="modal modal-blur fade" id="modal-edit-scent" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form id="edit-scent-form" method="post" action="update_scent.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $lang['dashboard_modal_edit_title'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <!-- Hidden Field für die ID -->
        <input type="hidden" name="id" id="edit-id">
        
        <!-- Code (optional) -->
        <div class="mb-3">
          <label class="form-label"><?= $lang['dashboard_modal_add_code'] ?></label>
          <input type="text" name="code" id="edit-code" class="form-control" placeholder="z. B. M-042">
        </div>
        <!-- Inspiriert von -->
        <div class="mb-3">
          <label class="form-label"><?= $lang['dashboard_modal_add_inspired_by'] ?></label>
          <input type="text" name="inspired_by" id="edit-inspired" class="form-control" required>
        </div>
        <!-- Geschlecht -->
        <div class="mb-3">
          <label class="form-label"><?= $lang['dashboard_modal_add_gender'] ?></label>
          <select name="gender" id="edit-gender" class="form-select" required>
            <option value="Herren">Herren</option>
            <option value="Damen">Damen</option>
            <option value="Unisex">Unisex</option>
          </select>
        </div>
        <!-- Beschreibung / Duftrichtung -->
        <div class="mb-3">
          <label class="form-label"><?= $lang['dashboard_modal_add_description'] ?></label>
          <textarea name="description" id="edit-description" class="form-control" rows="3" placeholder="z.B. Süß, Würzig, Holzig"></textarea>
        </div>
        
                            <!-- Switch: Duftpyramide verwenden? -->
                            <div class="mb-3">
                    <label class="form-check form-switch form-switch-2">
                        <input class="form-check-input" type="checkbox" name="use_pyramid" id="edit-usePyramidSwitch">
                        <span class="form-check-label"><?= $lang['dashboard_modal_add_pyramid_switch'] ?></span>
                    </label>
                    </div>

        <!-- Duftpyramide -->
        <div id="edit-pyramidFields">
        <h4><?= $lang['dashboard_modal_scent_pyramid'] ?></h4>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label"><?= $lang['dashboard_modal_add_top_notes'] ?></label>
            <select name="notes_top[]" id="edit-notes_top" class="form-select" multiple></select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label"><?= $lang['dashboard_modal_add_heart_notes'] ?></label>
            <select name="notes_heart[]" id="edit-notes_heart" class="form-select" multiple></select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label"><?= $lang['dashboard_modal_add_base_notes'] ?></label>
            <select name="notes_base[]" id="edit-notes_base" class="form-select" multiple></select>
          </div>
        </div>
        </div>
        
        <div id="edit-generalNotesBlock" style="display: none;">
  <h4><?= $lang['dashboard_modal_add_general_notes_title'] ?></h4>
  <div class="mb-3">
    <label class="form-label"><?= $lang['dashboard_modal_add_general_notes_label'] ?></label>
    <select name="notes_all[]" id="edit-notes_all" class="form-select" multiple></select>
  </div>
</div>

        <!-- Eigenschaften (%-Angaben) -->
        <h4><?= $lang['dashboard_modal_add_attributes_title'] ?></h4>
        <div class="row">
          <!-- Dufttyp -->
          <div class="mb-3">
            <label class="form-label"><?= $lang['dashboard_modal_add_type'] ?></label>
            <div id="edit-dufttyp-container">
              <!-- Hier folgt standardmäßig eine Input-Gruppe – später können dynamisch weitere Zeilen eingefügt werden -->
              <div class="input-group mb-2">
                <input type="text" name="dufttyp_label[]" class="form-control" placeholder="z. B. Süß">
                <input type="number" name="dufttyp_value[]" class="form-control" placeholder="40" min="0" max="100">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAttributeRow('edit-dufttyp')"><?= $lang['dashboard_modal_add_type_add'] ?></button>
          </div>
          
          <!-- Stil -->
          <label class="form-label"><?= $lang['dashboard_modal_add_style'] ?></label>
          <div class="row g-2">
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_men'] ?> </span>
                <input type="number" name="stil_Herren" id="edit-stil_Herren" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_women'] ?> </span>
                <input type="number" name="stil_Damen" id="edit-stil_Damen" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_classic'] ?> </span>
                <input type="number" name="stil_Klassisch" id="edit-stil_Klassisch" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_modern'] ?> </span>
                <input type="number" name="stil_Modern" id="edit-stil_Modern" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
          </div>
          
          <!-- Jahreszeit -->
          <label class="form-label"><?= $lang['dashboard_modal_add_season'] ?></label>
          <div class="row g-2">
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_spring'] ?> </span>
                <input type="number" name="season_Fruehling" id="edit-season_Fruehling" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_summer'] ?> </span>
                <input type="number" name="season_Sommer" id="edit-season_Sommer" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_autumn'] ?> </span>
                <input type="number" name="season_Herbst" id="edit-season_Herbst" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_winter'] ?> </span>
                <input type="number" name="season_Winter" id="edit-season_Winter" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
          </div>
          
          <!-- Anlass -->
          <label class="form-label"><?= $lang['dashboard_modal_add_occasion'] ?></label>
          <div class="row g-2">
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_daily'] ?> </span>
                <input type="number" name="occasion_Taeglich" id="edit-occasion_Taeglich" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_leisure'] ?> </span>
                <input type="number" name="occasion_Freizeit" id="edit-occasion_Freizeit" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_going_out'] ?> </span>
                <input type="number" name="occasion_Ausgehen" id="edit-occasion_Ausgehen" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_work'] ?> </span>
                <input type="number" name="occasion_Arbeit" id="edit-occasion_Arbeit" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_evening'] ?> </span>
                <input type="number" name="occasion_Abend" id="edit-occasion_Abend" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <span class="input-group-text"> <?= $lang['label_sport'] ?> </span>
                <input type="number" name="occasion_Sport" id="edit-occasion_Sport" class="form-control" placeholder="subdomain" autocomplete="off">
                <span class="input-group-text"> % </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
        <button type="submit" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" 
               viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" 
               stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 5l0 14"/>
            <path d="M5 12l14 0"/>
          </svg>
          <?= $lang['btn_save'] ?>
        </button>
      </div>
    </form>
  </div>
</div>


    <!-- Modalbox Duft Löschen -->
    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" aria-hidden="true">
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
                    <h3><?= $lang['dashboard_modal_delete_title'] ?></h3>
                    <?= sprintf($lang['dashboard_modal_delete_text'], '<strong id="delete-scent-full"></strong>') ?>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
                            </div>
                            <div class="col">
                                <form method="post" action="delete_scent.php" id="confirm-delete-form">
                                    <input type="hidden" name="id" id="delete-id">
                                    <button type="submit" class="btn btn-danger w-100"><?= $lang['btn_delete'] ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabler Core -->
    <script src="../assets/js/tabler.min.js" defer></script>
    <script src="../assets/js/demo.min.js" defer></script>
    <script src="../assets/js/tom-select.complete.js" defer></script>


<script>
// 1) Funktion im globalen Scope: Umschalten der Felder (Duftpyramide vs. Allgemeine Noten)
function toggleEditPyramidFields() {
  const switchEl       = document.getElementById("edit-usePyramidSwitch");
  const pyramidFields  = document.getElementById("edit-pyramidFields");
  const generalNotes   = document.getElementById("edit-generalNotesBlock");

  if (!switchEl || !pyramidFields || !generalNotes) return;

  // Schalter an => Kopf/Herz/Basis / Schalter aus => Allgemeine Noten
  if (switchEl.checked) {
    pyramidFields.style.display = "block";
    generalNotes.style.display  = "none";
  } else {
    pyramidFields.style.display = "none";
    generalNotes.style.display  = "block";
  }
}

// 2) Funktion zum Öffnen des Bearbeiten-Modals
function openEditModal(id) {
  fetch("load_scent.php?id=" + id)
    .then(response => response.json())
    .then(data => {
      console.log("Geladene Duftdaten:", data);

      // Standardfelder
      document.getElementById("edit-id").value          = data.id;
      document.getElementById("edit-code").value        = data.code;
      document.getElementById("edit-inspired").value    = data.inspired_by;
      document.getElementById("edit-gender").value      = data.gender;
      document.getElementById("edit-description").value = data.description;

      // Switch abgleichen mit use_pyramid
      const switchEl = document.getElementById("edit-usePyramidSwitch");
      if (switchEl) {
        switchEl.checked = (data.use_pyramid == 1);
        toggleEditPyramidFields(); // Sofort Felder anzeigen
        switchEl.addEventListener("change", toggleEditPyramidFields, { once: false });
      }

      // 2a) TomSelect-Instanzen für Pyramide
      const tsTop   = document.getElementById("edit-notes_top")?.tomselect;
      const tsHeart = document.getElementById("edit-notes_heart")?.tomselect;
      const tsBase  = document.getElementById("edit-notes_base")?.tomselect;

      // 2b) TomSelect-Instanz für Allgemeine Noten
      const tsAll   = document.getElementById("edit-notes_all")?.tomselect;
      

      // 3) Wenn use_pyramid=1 => Pyramiden-Felder befüllen
      if (data.use_pyramid == 1) {
        if (tsTop && data.notes_top) {
          tsTop.setValue(data.notes_top.split(",").map(s => s.trim()));
        }
        if (tsHeart && data.notes_heart) {
          tsHeart.setValue(data.notes_heart.split(",").map(s => s.trim()));
        }
        if (tsBase && data.notes_base) {
          tsBase.setValue(data.notes_base.split(",").map(s => s.trim()));
        }
      } else {
        // 4) Wenn use_pyramid=0 => Allgemeine Noten
        if (tsAll && data.notes_all) {
          tsAll.setValue(data.notes_all.split(",").map(s => s.trim()));
        }
      }

      // Dynamischer Bereich: Dufttyp
      const dufttypContainer = document.getElementById("edit-dufttyp-container");
      dufttypContainer.innerHTML = "";
      if (data.dufttyp) {
        const dufttypItems = data.dufttyp.split(",");
        dufttypItems.forEach(item => {
          const parts = item.split(":");
          const label = parts[0] ? parts[0].trim() : "";
          const value = parts[1] ? parts[1].trim() : "";
          const div   = document.createElement("div");
          div.className = "input-group mb-2";
          div.innerHTML = `
            <input type="text" name="dufttyp_label[]" class="form-control" placeholder="z. B. Süß" value="${label}">
            <input type="number" name="dufttyp_value[]" class="form-control" placeholder="40" min="0" max="100" value="${value}">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
          `;
          dufttypContainer.appendChild(div);
        });
      }

      // Helper-Funktion zum Setzen von Feldern (Stil, Season, Occasion)
      function setFieldsFromString(str, mapping) {
        if (!str) return;
        const pairs = str.split(",");
        pairs.forEach(pair => {
          const parts = pair.split(":");
          if (parts.length >= 2) {
            const key   = parts[0].trim();
            const value = parts[1].trim();
            const fieldId = mapping[key];
            if (fieldId) {
              document.getElementById(fieldId).value = value;
            }
          }
        });
      }

      // Mappings
      const stilMapping = {
        "Herren":    "edit-stil_Herren",
        "Damen":     "edit-stil_Damen",
        "Klassisch": "edit-stil_Klassisch",
        "Modern":    "edit-stil_Modern"
      };
      const seasonMapping = {
        "Frühling":  "edit-season_Fruehling",
        "Sommer":    "edit-season_Sommer",
        "Herbst":    "edit-season_Herbst",
        "Winter":    "edit-season_Winter"
      };
      const occasionMapping = {
        "Täglich":   "edit-occasion_Taeglich",
        "Freizeit":  "edit-occasion_Freizeit",
        "Ausgehen":  "edit-occasion_Ausgehen",
        "Arbeit":    "edit-occasion_Arbeit",
        "Abend":     "edit-occasion_Abend",
        "Sport":     "edit-occasion_Sport"
      };

      setFieldsFromString(data.stil,     stilMapping);
      setFieldsFromString(data.season,   seasonMapping);
      setFieldsFromString(data.occasion, occasionMapping);

      // Modalbox anzeigen
      const modal = new bootstrap.Modal(document.getElementById("modal-edit-scent"));
      modal.show();
    })
    .catch(error => {
      console.error("Fehler beim Laden der Duftdaten:", error);
    });
}
</script>




    <!-- Formular Hinzufügen  -->
<script>
    function addAttributeRow(category) {
        const container = document.getElementById(`${category}-container`);
        const div = document.createElement("div");
        div.className = "input-group mb-2";
        div.innerHTML = `
    <input type="text" name="${category}_label[]" class="form-control" placeholder="Bezeichnung">
    <input type="number" name="${category}_value[]" class="form-control" placeholder="%" min="0" max="100">
    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
  `;
        container.appendChild(div);
    }
</script>

<!-- TomSelect für Duft hinzufügen / Bearbeiten (Kopfnoten, Herznoten, Basisnoten) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("load_notes.php")
        .then(r => r.json())
        .then(commonNotes => {
            
            // Hier alle Selectoren, die wir mit TomSelect belegen möchten:
            const allSelectors = [
                "#notes_top",
                "#notes_heart",
                "#notes_base",
                "#notes_all",          // <--- neu hinzugefügt
                "#edit-notes_all",
                "#edit-notes_top",
                "#edit-notes_heart",
                "#edit-notes_base"
                // "#edit-notes_all" könntest du ggf. auch ergänzen, falls du das in der Bearbeiten-Modal brauchst
            ];

            allSelectors.forEach(selector => {
                // Prüfen, ob Element existiert (falls mal eines nicht im DOM steht)
                const el = document.querySelector(selector);
                if (el) {
                    new TomSelect(el, {
                        valueField: 'value',
                        labelField: 'text',
                        searchField: ['text'],
                        options: JSON.parse(JSON.stringify(commonNotes)),
                        plugins: ['remove_button'],
                        create: false,
                        persist: false,
                        render: {
                            option: function(data, escape) {
                                return `<div>
                                    <img src="${escape(data.image)}" 
                                         style="height:20px; width:20px; object-fit:cover; border-radius:50%; margin-right:8px;">
                                    ${escape(data.text)}
                                </div>`;
                            },
                            item: function(data, escape) {
                                return `<div>
                                    <img src="${escape(data.image)}" 
                                         style="height:16px; width:16px; object-fit:cover; border-radius:50%; margin-right:4px;">
                                    ${escape(data.text)}
                                </div>`;
                            }
                        }
                    });
                }
            });
        });
});
</script>

    <!-- Suchen, Einträge, Pageination -->
<script>
    const searchInput = document.getElementById("searchInput");
    const entriesInput = document.getElementById("entriesCount");
    const tbody = document.getElementById("scentsTableBody");
    const pagination = document.getElementById("pagination");
    const paginationInfo = document.getElementById("pagination-info");
    const originalRows = Array.from(tbody.querySelectorAll("tr"));

    let currentPage = 1;

    function getFilteredRows() {
        const query = searchInput.value.toLowerCase();
        return originalRows.filter(row => row.textContent.toLowerCase().includes(query));
    }

    function renderPagination(totalPages) {
        pagination.innerHTML = "";

        const createPageItem = (label, page, disabled = false, active = false) => {
            const li = document.createElement("li");
            li.className = `page-item${disabled ? " disabled" : ""}${active ? " active" : ""}`;
            const a = document.createElement("a");
            a.className = "page-link";
            a.href = "#";
            a.textContent = label;
            a.onclick = (e) => {
                e.preventDefault();
                if (!disabled && currentPage !== page) {
                    currentPage = page;
                    renderTable();
                }
            };
            li.appendChild(a);
            return li;
        };

        pagination.appendChild(createPageItem("«", currentPage - 1, currentPage === 1));
        for (let i = 1; i <= totalPages; i++) {
            pagination.appendChild(createPageItem(i, i, false, i === currentPage));
        }
        pagination.appendChild(createPageItem("»", currentPage + 1, currentPage === totalPages));
    }

    function renderTable() {
        const limit = parseInt(entriesInput.value, 10) || originalRows.length;
        const filteredRows = getFilteredRows();
        const totalEntries = filteredRows.length;
        const totalPages = Math.ceil(totalEntries / limit);
        const start = (currentPage - 1) * limit;
        const end = start + limit;

        tbody.innerHTML = "";

        if (totalEntries === 0) {
            tbody.innerHTML = "<tr><td colspan='7'>Keine Einträge gefunden.</td></tr>";
            pagination.innerHTML = "";
            paginationInfo.innerHTML = "Keine Einträge gefunden.";
            return;
        }

        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));
        paginationInfo.innerHTML =
            `Zeige <span>${start + 1}</span> bis <span>${Math.min(end, totalEntries)}</span> von <span>${totalEntries}</span> Einträgen`;

        renderPagination(totalPages);
    }

    searchInput.addEventListener("input", () => {
        currentPage = 1;
        renderTable();
    });
    entriesInput.addEventListener("input", () => {
        currentPage = 1;
        renderTable();
    });

    renderTable();
</script>

    <!-- Löschen Modalbox -->
<script>
    function confirmDelete(id, code, name) {
        document.getElementById("delete-id").value = id;
        document.getElementById("delete-scent-full").textContent = code + " – " + name;

        const modal = new bootstrap.Modal(document.getElementById("modal-danger"));
        modal.show();
    }
</script>


<!-- Switch-Skript für das HINZUFÜGEN-Modal -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const pyramidSwitchAdd = document.getElementById("usePyramidSwitch");  // ID aus dem "Add"-Modal
    const pyramidFieldsAdd = document.getElementById("pyramidFields");
    const generalNotesAdd  = document.getElementById("generalNotesBlock");

    function toggleAddPyramidFields() {
        if (pyramidSwitchAdd && pyramidFieldsAdd && generalNotesAdd) {
            if (pyramidSwitchAdd.checked) {
                pyramidFieldsAdd.style.display = "block";
                generalNotesAdd.style.display  = "none";
            } else {
                pyramidFieldsAdd.style.display = "none";
                generalNotesAdd.style.display  = "block";
            }
        }
    }

    // Nur ausführen, falls die Elemente existieren (Add-Modal ist auf der Seite):
    if (pyramidSwitchAdd) {
        toggleAddPyramidFields();
        pyramidSwitchAdd.addEventListener("change", toggleAddPyramidFields);
    }
});
</script>

<!-- Switch-Skript für das BEARBEITEN-Modal -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const pyramidSwitchEdit = document.getElementById("edit-usePyramidSwitch"); // ID aus dem "Edit"-Modal
    const pyramidFieldsEdit = document.getElementById("edit-pyramidFields");
    const generalNotesEdit  = document.getElementById("edit-generalNotesBlock");

    function toggleEditPyramidFields() {
        const tsTop   = document.getElementById("edit-notes_top")?.tomselect;
        const tsHeart = document.getElementById("edit-notes_heart")?.tomselect;
        const tsBase  = document.getElementById("edit-notes_base")?.tomselect;
        const tsAll   = document.getElementById("edit-notes_all")?.tomselect;

        if (pyramidSwitchEdit && pyramidFieldsEdit && generalNotesEdit) {
            if (pyramidSwitchEdit.checked) {
                // Duftpyramide aktiv → allgemeine Noten leeren
                if (tsAll) tsAll.clear();

                pyramidFieldsEdit.style.display = "block";
                generalNotesEdit.style.display  = "none";
            } else {
                // Allgemeine Noten aktiv → Pyramide leeren
                if (tsTop) tsTop.clear();
                if (tsHeart) tsHeart.clear();
                if (tsBase) tsBase.clear();

                pyramidFieldsEdit.style.display = "none";
                generalNotesEdit.style.display  = "block";
            }
        }
    }

    // Nur ausführen, falls die Elemente existieren (Edit-Modal ist auf der Seite):
    if (pyramidSwitchEdit) {
        toggleEditPyramidFields();
        pyramidSwitchEdit.addEventListener("change", toggleEditPyramidFields);
    }
});
</script>






</body>

</html>