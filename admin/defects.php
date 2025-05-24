<?php
// admin/defects.php
session_start();
require_once "db.php";
require_once "alerts.php";

// Sprachdatei laden
require_once dirname(__DIR__) . '/lang/lang.php';
if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}


// Zugriffsschutz
if (!isset($_SESSION["admin"]) || !in_array($_SESSION["admin_role"], ["admin", "editor"])) {
    setAlert("error", "Du hast keine Berechtigung, diese Seite zu sehen.");
    header("Location: dashboard.php");
    exit;
}

// Meldungen abrufen
$stmt = $pdo->query("SELECT * FROM defects ORDER BY created_at DESC");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Defekte</title>
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
                            <li class="nav-item">
                                <a class="nav-link" href="notes.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-droplet"></i>
                                    </span>
                                    <span class="nav-link-title"> <?= $lang['nav_notes'] ?> </span>
                                </a>
                            </li>
                            <!-- Defekte -->
                            <li class="nav-item active">
                                <a class="nav-link" href="notes.php">
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
                            <h2 class="page-title"><?= $lang['defects_page_title'] ?></h2>
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
                        <div class="card-header">
                            <h3 class="card-title"><?= $lang['defects_table_title'] ?></h3>
                        </div>
                        <div class="card-body border-bottom py-3">

                            <div class="d-flex">
                                <div class="text-secondary">
                                    <?= $lang['pagination_showing'] ?>
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" id="entriesCount" class="form-control form-control-sm"
                                            value="10" min="1" size="3" aria-label="Einträge anzeigen">
                                    </div>
                                    <?= $lang['pagination_entries'] ?>
                                </div>
                                <div class="ms-auto text-secondary">
                                    <?= $lang['defects_label_filter'] ?>
                                    <div class="ms-2 d-inline-block">
                                        <select id="problemFilter" class="form-select form-select-sm">
                                            <option value=""><?= $lang['defects_filter_all'] ?></option>
                                            <option value="Pumpspray defekt"><?= $lang['defects_filter_spray'] ?>
                                            </option>
                                            <option value="Flasche ausgelaufen"><?= $lang['defects_filter_leak'] ?>
                                            </option>
                                            <option value="Falscher Duft erhalten">
                                                <?= $lang['defects_filter_wrong_scent'] ?></option>
                                            <option value="Sonstiges"><?= $lang['defects_filter_other'] ?></option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><?= $lang['defects_column_number'] ?></th>
                                        <th><?= $lang['defects_column_date'] ?></th>
                                        <th><?= $lang['defects_column_scent'] ?></th>
                                        <th><?= $lang['defects_column_problem'] ?></th>
                                        <th><?= $lang['defects_column_contact'] ?></th>
                                        <th><?= $lang['defects_column_message'] ?></th>
                                        <th><?= $lang['defects_column_image'] ?></th>
                                    </tr>
                                </thead>
                                <tbody id="defectsTableBody">
                                    <?php foreach ($reports as $i => $r): ?>
                                    <?php $modalId = "msgModal" . $r["id"]; $preview = mb_strimwidth($r["message"], 0, 100, '...'); ?>
                                    <?php
$msgStmt = $pdo->prepare("
    SELECT m.message, m.created_at, m.user_id, u.first_name, u.last_name
    FROM defect_messages m
    LEFT JOIN users u ON m.user_id = u.id
    WHERE m.defect_id = ?
    ORDER BY m.created_at ASC
");
$msgStmt->execute([$r["id"]]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);
?>
                                    <tr class="clickable-row" data-modal-id="<?= $modalId ?>" style="cursor: pointer;">
                                        <td><?= $i + 1 ?></td>
                                        <td><?= date("d.m.Y H:i", strtotime($r["created_at"])) ?></td>
                                        <td><?= htmlspecialchars($r["duft"]) ?></td>
                                        <td><?= htmlspecialchars($r["problem"]) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($r["name"]) ?></strong><br>
                                            <a href="mailto:<?= htmlspecialchars($r["email"]) ?>"
                                                onclick="event.stopPropagation();">
                                                <?= htmlspecialchars($r["email"]) ?>
                                            </a>
                                        </td>
                                        <td style="max-width: 250px;"><?= htmlspecialchars($preview) ?></td>
                                        <td>
                                            <?php if (!empty($r["image"]) && file_exists(__DIR__ . "/../uploads/defekte/" . $r["image"])): ?>
                                            <a data-fslightbox="defect-gallery"
                                                href="../uploads/defekte/<?= htmlspecialchars($r["image"]) ?>"
                                                onclick="event.stopPropagation();">
                                                <div class="rounded border"
                                                    style="width: 40px; height: 40px; background-image: url('../uploads/defekte/<?= htmlspecialchars($r["image"]) ?>'); background-size: cover; background-position: center;">
                                                </div>
                                            </a>
                                            <?php else: ?>
                                            <?= $lang['defects_image_placeholder'] ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1"
                                        aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="<?= $modalId ?>Label">
                                                        <?= $lang['defects_modal_title'] ?? 'Defektmeldung' ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="<?= $lang['btn_close'] ?? 'Schließen' ?>"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <strong><?= $lang['defects_column_name'] ?? 'Name' ?>:</strong>
                                                        <?= htmlspecialchars($r["name"]) ?>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong><?= $lang['defects_column_email'] ?? 'E-Mail' ?>:</strong>
                                                        <a
                                                            href="mailto:<?= htmlspecialchars($r["email"]) ?>"><?= htmlspecialchars($r["email"]) ?></a>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong><?= $lang['defects_column_scent'] ?>:</strong>
                                                        <?= htmlspecialchars($r["duft"]) ?>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong><?= $lang['defects_column_problem'] ?>:</strong>
                                                        <?= htmlspecialchars($r["problem"]) ?>
                                                    </div>

                                                    <div>
                                                        <strong><?= $lang['defects_column_message'] ?? 'Nachricht' ?>:</strong><br>
                                                        <pre
                                                            style="white-space: pre-wrap; word-break: break-word; font-family: inherit;">
                                                            <?= htmlspecialchars($r["message"]) ?></pre>
                                                    </div>

                                                    <?php if (!empty($r["image"]) && file_exists(__DIR__ . "/../uploads/defekte/" . $r["image"])): ?>
                                                    <hr>
                                                    <div class="mb-2">
                                                        <strong><?= $lang['defects_column_image'] ?>:</strong><br>
                                                        <img src="../uploads/defekte/<?= htmlspecialchars($r["image"]) ?>"
                                                            alt="Bild" style="max-width: 100%; border-radius: 8px;">
                                                    </div>
                                                    <?php endif; ?>
                                                    <hr>
                                                    <h5>Antwort senden</h5>
                                                    <form method="post" action="send_defect_reply.php">
                                                        <input type="hidden" name="defect_id" value="<?= $r['id'] ?>">
                                                        <textarea name="message" class="form-control mb-2" rows="3"
                                                            required placeholder="Antwort..."></textarea>
                                                        <button type="submit"
                                                            class="btn btn-primary"><?= $lang['btn_send'] ?? 'Senden' ?></button>
                                                    </form>


                                                    <?php if ($messages): ?>
                                                    <hr>
                                                    <h5>Verlauf</h5>
                                                    <div class="border rounded p-2"
                                                        style="max-height: 250px; overflow-y: auto; background: #f8f9fa;">
                                                        <?php foreach ($messages as $m): ?>
                                                        <div class="mb-2">
                                                            <small
                                                                class="text-muted"><?= date("d.m.Y H:i", strtotime($m["created_at"])) ?>
                                                                ·
                                                                <?= $m["user_id"] ? htmlspecialchars($m["first_name"] . ' ' . $m["last_name"]) : 'Kunde' ?>
                                                            </small><br>
                                                            <div style="white-space: pre-wrap">
                                                                <?= htmlspecialchars($m["message"]) ?></div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <?php endif; ?>

                                                    <?php if ($r["customer_id"]): ?>
                                                    <hr>
                                                    <button class="btn btn-outline-secondary w-100" type="button"
                                                        onclick="loadCustomerDefects(<?= (int) $r['customer_id'] ?>, '<?= $modalId ?>')">
                                                        Weitere Meldungen dieses Kunden anzeigen
                                                    </button>
                                                    <div id="more-defects-<?= $modalId ?>" class="mt-2"
                                                        style="display:none;"></div>
                                                    <?php endif; ?>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <?= $lang['btn_close'] ?? 'Schließen' ?>
                                                    </button>
                                                    <form method="post" action="mark_defect_done.php" class="me-auto">
                                                        <input type="hidden" name="defect_id" value="<?= $r['id'] ?>">
                                                        <input type="hidden" name="done"
                                                            value="<?= $r['is_done'] ? 0 : 1 ?>">
                                                        <button type="submit"
                                                            class="btn <?= $r['is_done'] ? 'btn-warning' : 'btn-success' ?>">
                                                            <?= $r['is_done'] ? 'Als offen markieren' : 'Als erledigt markieren' ?>
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

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





    </div>

    <!-- Tabler Core -->
    <script src="../assets/js/tabler.min.js" defer></script>
    <script src="../assets/js/demo.min.js" defer></script>
    <script src="../assets/js/lightbox.js" defer></script>


    <script>
    const filterSelect = document.getElementById("problemFilter");
    const entriesInput = document.getElementById("entriesCount");
    const tbody = document.getElementById("defectsTableBody");
    const pagination = document.getElementById("pagination");
    const paginationInfo = document.getElementById("pagination-info");
    const originalRows = Array.from(tbody.querySelectorAll("tr"));

    let currentPage = 1;

    function getFilteredRows() {
        const selectedProblem = filterSelect.value;
        return originalRows.filter(row => {
            const problemCell = row.cells[3]; // 4. Spalte = Problem
            if (!problemCell) return false;
            const cellValue = problemCell.textContent.trim();
            return selectedProblem === "" || cellValue === selectedProblem;
        });
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
            tbody.innerHTML = "<tr><td colspan='7'><?= $lang['defects_no_results'] ?></td></tr>";
            pagination.innerHTML = "";
            paginationInfo.innerHTML = "<?= $lang['defects_pagination_none'] ?>";
            return;
        }

        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));

        paginationInfo.innerHTML =
            `Zeige <span>${start + 1}</span> bis <span>${Math.min(end, totalEntries)}</span> von <span>${totalEntries}</span> Einträgen`;

        renderPagination(totalPages);
    }

    filterSelect.addEventListener("change", () => {
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

    <!-- Success & Error Messages -->
    <script>
    setTimeout(function() {
        document.querySelectorAll('.alert-dismissible').forEach(function(alertBox) {
            alertBox.classList.remove('show');
        });
    }, 4000); // Nach 4 Sekunden ausblenden
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Lightbox bleibt wie sie ist – kein Eingriff
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', () => {
                const modalId = row.getAttribute('data-modal-id');
                if (modalId) {
                    const modalElement = document.getElementById(modalId);
                    const modalInstance = new bootstrap.Modal(modalElement);
                    modalInstance.show();
                }
            });
        });

        // Bild-Links vollständig vom Row-Klick entkoppeln
        document.querySelectorAll('[data-fslightbox]').forEach(link => {
            link.addEventListener('click', e => {
                e
                    .stopImmediatePropagation(); // verhindert wirklich alles – auch bubbling UND capturing
            });
        });
    });
    </script>

    <script>
    function loadCustomerDefects(customerId, modalId) {
        const container = document.getElementById("more-defects-" + modalId);
        if (container.style.display === "none") {
            container.innerHTML = "Lade weitere Meldungen...";
            fetch("load_customer_defects.php?id=" + customerId)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                    container.style.display = "block";
                });
        } else {
            container.style.display = "none";
        }
    }
    </script>


</body>

</html>