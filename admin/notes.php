<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

require_once "db.php"; // Datenbank einbinden
require_once "alerts.php"; // Zentrale Alert-Funktion einbinden

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// CSRF-Token erzeugen (falls noch nicht vorhanden)
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Duftnoten abrufen
$notes = $pdo->query("SELECT * FROM notes ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Notes</title>
    <!-- CSS files -->
    <link href="../assets/css/tabler.min.css" rel="stylesheet" />
    <link href="../assets/css/demo.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
    <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }

    table.table {
        table-layout: fixed;
        width: 100%;
    }

    table.table th,
    table.table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    table.table td img {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        table.table {
            display: block;
            overflow-x: auto;
        }
    }

    table.table th:first-child,
    table.table td:first-child {
        width: 50px;
        max-width: 50px;
        text-align: center;
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
                                <!-- Download SVG icon from http://tabler.io/icons/icon/moon -->
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
                                <!-- Download SVG icon from http://tabler.io/icons/icon/sun -->
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
                            <li class="nav-item">
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
                            <li class="nav-item active">
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
                            <h2 class="page-title"><?= $lang['notes_page_title'] ?></h2>
                        </div>
                        <!-- Success & Error Messages -->
                        <?php showAlerts(); ?>
                        <!-- Neue Duftnote Btn -->
                        <?php if (in_array($_SESSION["admin_role"], ["admin", "editor"])): ?>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal-add-note">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    <?= $lang['notes_add_button'] ?>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= $lang['notes_table_title'] ?></h3>
                        </div>
                        <div class="card-body border-bottom py-3">

                            <div class="d-flex">
                                <div class="text-secondary">
                                    <?= $lang['pagination_showing'] ?>
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" id="entriesCount" class="form-control form-control-sm"
                                            value="10" min="1" size="3" aria-label="Einträge anzeigen">
                                    </div>
                                    Duftnoten
                                </div>
                                <div class="ms-auto text-secondary">
                                    Suchen:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" id="noteSearch" class="form-control form-control-sm"
                                            aria-label="Search Notes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><?= $lang['notes_table_column_number'] ?></th>
                                        <th><?= $lang['notes_table_column_name'] ?></th>
                                        <th class="text-end"><?= $lang['notes_table_column_actions'] ?></th>
                                    </tr>
                                </thead>
                                <tbody id="notesTableBody">
                                    <?php foreach ($notes as $i => $note): ?>
                                    <?php
                                        $slug = strtolower($note["name"]);
                                        $slug = str_replace(
                                            ["ä", "ö", "ü", "ß", " ", "é", "è", "à", "á", "ô", "î", "â", "ë", "ê", "ç"],
                                            ["ae", "oe", "ue", "ss", "_", "e", "e", "a", "a", "o", "i", "a", "e", "e", "c"],
                                            $slug
                                        );
                                        $slug = preg_replace('/[^a-z0-9_]/', '', $slug);
                                        $image = file_exists("../assets/img/notes/{$slug}.webp") ? "{$slug}.webp" : "default.webp";
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <img src="../assets/img/notes/<?= $image ?>"
                                                alt="<?= htmlspecialchars($note["name"]) ?>"
                                                style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; margin-right: 8px;">
                                            <?= htmlspecialchars($note["name"]) ?>
                                        </td>
                                        <!-- Bearbeiten / Löschen Btn -->
                                        <td class="text-end">
                                        <?php if (in_array($_SESSION["admin_role"], ["admin", "editor"])): ?>
                                            <a href="#" class="text-primary me-2"
                                                onclick="editNote(<?= $note['id'] ?>, '<?= htmlspecialchars($note['name'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="text-danger"
                                                onclick="confirmDeleteNote(<?= $note['id'] ?>, '<?= htmlspecialchars($note['name']) ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary" id="pagination-info">Zeige <span>1</span> bis <span>8</span>
                                von <span>16</span> Einträgen</p>
                            <ul class="pagination m-0 ms-auto" id="pagination">
                                <!-- Hier wird dynamisch eingefügt -->
                            </ul>
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
    <!-- Modal: Neue Duftnote -->
    <div class="modal fade" id="modal-add-note" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="save_note.php" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $lang['notes_add_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['notes_add_label_name'] ?></label>
                        <input type="text" name="name" class="form-control" placeholder="z. B. Vanille" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['notes_add_label_image'] ?></label>
                        <input type="file" name="image" class="form-control" accept=".webp,.jpg,.jpeg,.png">
                        <small class="form-hint"><?= $lang['notes_add_hint_image'] ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></button>
                    <button type="submit" class="btn btn-primary"><?= $lang['btn_add'] ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Duftnote Bearbeiten -->
    <div class="modal fade" id="modal-edit-note" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="edit_note.php" method="post" enctype="multipart/form-data" class="modal-content">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $lang['notes_edit_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Name -->
                    <label class="form-label"><?= $lang['notes_add_label_name'] ?></label>
                    <input type="text" name="name" id="edit_name" class="form-control" placeholder="z. B. Vanille"
                        required>

                    <!-- Bild-Vorschau -->
                    <div class="mt-3">
                        <label class="form-label"><?= $lang['notes_edit_label_current_image'] ?></label>
                        <br>
                        <img id="edit_image_preview" src="" alt="Bildvorschau" class="rounded"
                            style="max-height: 100px;">
                    </div>

                    <!-- Bild ersetzen -->
                    <div class="mt-3">
                        <label class="form-label"><?= $lang['notes_edit_label_replace_image'] ?></label>
                        <input type="file" name="image" accept=".webp" class="form-control">
                        <small class="form-hint"><?= $lang['notes_edit_hint_replace_image'] ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></button>
                    <button type="submit" class="btn btn-primary"><?= $lang['btn_save'] ?></button>
                </div>
            </form>
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
                    <h3><?= $lang['notes_delete_confirm_title'] ?></h3>
                    <div class="text-secondary">
    <?= str_replace('{name}', '<strong id="delete-note-name"></strong>', $lang['notes_delete_confirm_text']) ?>
</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
                            </div>
                            <div class="col">
                                <form method="post" action="delete_note.php" id="confirm-delete-note-form">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
                                    <input type="hidden" name="id" id="delete-note-id">
                                    <button type="submit" class="btn btn-danger w-100"><?= $lang['btn_delete'] ?></button>
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
    <script src="../assets/js/tabler.min.js" defer></script>
    <script src="../assets/js/demo.min.js" defer></script>


    <!-- Note Bearbeiten -->
    <script>
    function editNote(id, name) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;

        // Slug wie beim Bild-Namen generieren
        let slug = name.toLowerCase()
            .replace(/ä/g, "ae").replace(/ö/g, "oe").replace(/ü/g, "ue")
            .replace(/ß/g, "ss").replace(/[éè]/g, "e").replace(/[àá]/g, "a")
            .replace(/ô/g, "o").replace(/î/g, "i").replace(/â/g, "a")
            .replace(/[ëê]/g, "e").replace(/ç/g, "c")
            .replace(/\s+/g, "_").replace(/[^a-z0-9_]/g, "");

        let img = `../assets/img/notes/${slug}.webp`;
        document.getElementById('edit_image_preview').src = img;

        // Modal anzeigen
        let modal = new bootstrap.Modal(document.getElementById('modal-edit-note'));
        modal.show();
    }
    </script>

    <script>
    const searchInput = document.getElementById("noteSearch");
    const entriesInput = document.getElementById("entriesCount");
    const tbody = document.getElementById("notesTableBody");
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

        // Prev
        pagination.appendChild(createPageItem("«", currentPage - 1, currentPage === 1));

        for (let i = 1; i <= totalPages; i++) {
            pagination.appendChild(createPageItem(i, i, false, i === currentPage));
        }

        // Next
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
            tbody.innerHTML = "<tr><td colspan='3'><?= $lang['notes_no_results'] ?></td></tr>";
            pagination.innerHTML = "";
            paginationInfo.innerHTML = "<?= $lang['notes_table_pagination_none'] ?>";
            return;
        }

        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));

        // Info aktualisieren
        paginationInfo.innerHTML =
            `Zeige <span>${start + 1}</span> bis <span>${Math.min(end, totalEntries)}</span> von <span>${totalEntries}</span> Einträgen`;

        // Pagination
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

    // Initial render
    renderTable();
    </script>

    <!-- Löschen Modalbox -->
    <script>
    function confirmDeleteNote(id, name) {
        document.getElementById("delete-note-id").value = id;
        document.getElementById("delete-note-name").textContent = name;

        const modal = new bootstrap.Modal(document.getElementById("modal-delete-note"));
        modal.show();
    }
    </script>

    <!-- Success & Error Messages -->
    <script>
    setTimeout(function() {
        document.querySelectorAll('.alert-dismissible').forEach(function(alertBox) {
            alertBox.classList.remove('show');
        });
    }, 4000); // Nach 4 Sekunden ausblenden
    </script>


</body>

</html>