<?php
session_start();

if (!isset($_SESSION['admin'])) {
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

$error = "";
$success = "";

// Benutzer hinzufügen (Formular absenden)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (($_SESSION['admin_role'] ?? '') !== 'admin') {
      exit("Nur Admins dürfen Benutzer hinzufügen.");
  }

  $first_name = trim($_POST["first_name"] ?? '');
  $last_name = trim($_POST["last_name"] ?? '');
  $username = trim($_POST["username"] ?? '');
  $email = trim($_POST["email"] ?? '');
  $password = $_POST["password"] ?? '';
  $role = $_POST["role"] ?? 'user'; // Rolle verarbeiten (Standard: user)

  if ($first_name && $last_name && $username && $email && $password && $role) {
      // Prüfen, ob Benutzername bereits existiert
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
      $stmt->execute([$username]);
      if ($stmt->fetchColumn() > 0) {
          $error = "Benutzername existiert bereits.";
      } else {
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->execute([$first_name, $last_name, $username, $email, $hashed, $role]);
          $success = "Benutzer erfolgreich erstellt.";
          header("Location: users.php"); // Seite neu laden
          exit;
      }
  } else {
      $error = "Bitte alle Felder ausfüllen.";
  }
}


// Benutzer löschen
if (isset($_GET['delete']) && $_SESSION['admin_role'] === 'admin') {
  $id = intval($_GET['delete']);
  if ($id > 0) {
      $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
      $stmt->execute([$id]);
      header("Location: users.php");
      exit;
  }
}


// Benutzerliste laden (inkl. Rolle)
$stmt = $pdo->query("SELECT id, first_name, last_name, username, email, role FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalUsers = count($users);


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
    <title>Users</title>
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
                            <li class="nav-item active">
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
                <div class="row g-2 align-items-center justify-content-between">
    <div class="col-md-6 col-sm-12">
        <h2 class="page-title mb-0">
        <?= $lang['users_title'] ?>
        </h2>
        <div class="text-secondary mt-1" id="userCountText">
            1–<?= $totalUsers ?> von <?= $totalUsers ?> Benutzern
        </div>
    </div>
    <div class="col-md-6 col-sm-12 d-print-none mt-3 mt-md-0 text-md-end">
        <div class="d-flex justify-content-md-end">
            <input type="search" class="form-control w-100 w-md-auto me-md-3" placeholder="Search user…" id="searchInput" />
            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                <a href="#" class="btn btn-primary ms-2 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#modal-add-user">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    <?= $lang['users_add_button'] ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
<!-- Success & Error Messages -->
<?php showAlerts(); ?>
                    <div class="row row-cards">
                        <?php foreach ($users as $user): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body p-4 text-center">
                                    <span
                                        class="avatar avatar-xl mb-3 rounded"><?= strtoupper(substr($user["first_name"], 0, 1) . substr($user["last_name"], 0, 1)) ?></span>
                                        <div class="text-muted small mb-1">
    <?= htmlspecialchars('@' . $user["username"]) ?>
</div>
                                        <h3 class="m-0 mb-1">
                                        <?= htmlspecialchars($user["first_name"] . ' ' . $user["last_name"]) ?></h3>
                                    <div class="text-secondary"><?= htmlspecialchars($user["email"]) ?></div>
                                    <div class="mt-3">
                                        <?php
                                            $role = strtolower($user["role"]);
                                            $badgeClass = match($role) {
                                                'admin' => 'bg-red-lt',
                                                'editor' => 'bg-purple-lt',
                                                'user' => 'bg-green-lt',
                                                default => 'bg-secondary',
                                            };
                                            ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($role) ?></span>
                                    </div>
                                </div>
                                <div class="d-flex">

                                    
                                    <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                                        <a href="#"
                                        class="card-btn text-blue"
                                        onclick="openEditUserModal(<?= htmlspecialchars(json_encode($user)) ?>)">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>
                                        <?= $lang['btn_edit'] ?>
                                        </a>

                                    <a href="users.php?delete=<?= $user['id'] ?>" class="card-btn text-danger"
                                        onclick="return confirm('Benutzer wirklich löschen?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7h16" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-2a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                        <?= $lang['btn_delete'] ?>
                                    </a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="noResults" class="text-center text-muted my-4" style="display:none;">
    <i class="fa-solid fa-user-slash fa-2x mb-2"></i><br>
    <?= $lang['users_no_results'] ?>
</div>

                </div>
            </div>
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
    <?php if ($_SESSION['admin_role'] === 'admin'): ?>
    <div class="modal modal-blur fade" id="modal-add-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $lang['users_add_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_role'] ?></label>
                        <select name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="editor">Editor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_firstname'] ?></label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_lastname'] ?></label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_username'] ?></label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_email'] ?></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= $lang['users_label_password'] ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <?= $lang['btn_add'] ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <form action="edit_user.php" method="post" class="modal-content">
      <input type="hidden" name="id" id="edit-user-id">
      <div class="modal-header">
        <h5 class="modal-title"><?= $lang['users_edit_title'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_role'] ?></label>
          <select name="role" id="edit-role" class="form-control" required>
            <option value="user">User</option>
            <option value="editor">Editor</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_firstname'] ?></label>
          <input type="text" name="first_name" id="edit-first-name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_lastname'] ?></label>
          <input type="text" name="last_name" id="edit-last-name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_username'] ?></label>
          <input type="text" name="username" id="edit-username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_email'] ?></label>
          <input type="email" name="email" id="edit-email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label"><?= $lang['users_label_new_password'] ?></label>
          <input type="password" name="password" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal"><?= $lang['btn_cancel'] ?></a>
        <button type="submit" class="btn btn-primary"><?= $lang['btn_save'] ?></button>
      </div>
    </form>
  </div>
</div>
    <?php endif; ?>
    </div>

    <!-- Tabler Core -->
    <script src="../assets/js/tabler.min.js" defer></script>
    <script src="../assets/js/demo.min.js" defer></script>

    <!-- Search User -->
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const userCards = document.querySelectorAll(".col-md-6.col-lg-3");
    const noResults = document.getElementById("noResults");

    searchInput.addEventListener("input", function () {
        const search = searchInput.value.toLowerCase();
        let visibleCount = 0;

        userCards.forEach(card => {
            const name = card.querySelector(".m-0.mb-1")?.textContent.toLowerCase() || "";
            const email = card.querySelector(".text-secondary")?.textContent.toLowerCase() || "";
            const username = card.querySelector(".card-body")?.innerText.toLowerCase().match(/Benutzername:\s*(\S+)/)?.[1] || "";
            const role = card.querySelector(".badge")?.textContent.toLowerCase() || "";

            if (name.includes(search) || email.includes(search) || username.includes(search) || role.includes(search)) {
                card.style.display = "";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        noResults.style.display = (visibleCount === 0) ? "block" : "none";
    });
});
</script>

<script>
function openEditUserModal(user) {
  document.getElementById('edit-user-id').value = user.id;
  document.getElementById('edit-first-name').value = user.first_name;
  document.getElementById('edit-last-name').value = user.last_name;
  document.getElementById('edit-username').value = user.username;
  document.getElementById('edit-email').value = user.email;
  document.getElementById('edit-role').value = user.role;

  const modal = new bootstrap.Modal(document.getElementById('modal-edit-user'));
  modal.show();
}
</script>

<script>
setTimeout(function() {
    document.querySelectorAll('.alert-dismissible').forEach(function(alertBox) {
        alertBox.classList.remove('show');
    });
}, 4000); // Nach 4 Sekunden ausblenden
</script>


</body>

</html>