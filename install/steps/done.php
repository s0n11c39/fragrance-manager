<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Installation abgeschlossen | Parfum Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../../assets/css/tabler.min.css" rel="stylesheet">
  <style>
    .icon-check {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background-color: #d3f9d8;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
    }
    .icon-check svg {
      width: 36px;
      height: 36px;
      color: #2fb344;
    }
  </style>
</head>
<body>
  <div class="page">
    <!-- Page wrapper -->
    <div class="page-wrapper">

      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card shadow-sm">
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
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Optional footer -->
      <footer class="footer footer-transparent d-print-none mt-5">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-muted">
              &copy; <?= date('Y') ?> Parfum Dashboard
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="../assets/js/tabler.min.js"></script>
</body>
</html>
