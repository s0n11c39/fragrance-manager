<?php
function setAlert($type, $message) {
    $_SESSION[$type] = $message;
}

/**
 * Diese Funktion überprüft, ob in der Session ein "success"- oder "error"-Eintrag existiert
 * und zeigt die entsprechende Bootstrap-Alert-Box im Tabler-Design an.
 */
function showAlerts() {
    if (!empty($_SESSION["success"])) {
        $msg = $_SESSION["success"];
        unset($_SESSION["success"]);

        echo <<<HTML
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
  <div class="alert-icon me-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon icon-2" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none">
      <path d="M5 12l5 5l10 -10"></path>
    </svg>
  </div>
  <div>$msg</div>
  <a class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="close"></a>
</div>
HTML;
    }

    if (!empty($_SESSION["error"])) {
        $msg = $_SESSION["error"];
        unset($_SESSION["error"]);

        echo <<<HTML
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
  <div class="alert-icon me-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon icon-2" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none">
      <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
      <path d="M12 8v4"></path>
      <path d="M12 16h.01"></path>
    </svg>
  </div>
  <div>$msg</div>
  <a class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="close"></a>
</div>
HTML;
    }
}
