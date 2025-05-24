<?php
// defekt.php
// Sprachdatei laden
require_once __DIR__ . '/lang/lang.php';

if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Düfte</title>

  <!-- CSS files -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/font-awesome.css">
  <link href="../assets/css/tabler.min.css" rel="stylesheet" />
  <link href="../assets/css/demo.min.css" rel="stylesheet" />
  <link href="../assets/css/tom-select.bootstrap5.min.css" rel="stylesheet">
  </head>
<div class="container my-4">
 <form action="submit_defekt.php" method="post" enctype="multipart/form-data" class="card card-md">
    <div class="card-body">
        <h2 class="card-title mb-4"><?= $lang['defect_title'] ?></h2>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_name'] ?></label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_email'] ?></label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_scent'] ?></label>
            <input type="text" name="duft" class="form-control" required placeholder="z. B. M-021 oder Sauvage">
        </div>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_problem'] ?></label>
            <select name="problem" class="form-select" required>
                <option value="Pumpspray defekt"><?= $lang['defect_option_spray'] ?></option>
                <option value="Flasche ausgelaufen"><?= $lang['defect_option_leak'] ?></option>
                <option value="Falscher Duft erhalten"><?= $lang['defect_option_wrong'] ?></option>
                <option value="Sonstiges"><?= $lang['defect_option_other'] ?></option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_note'] ?></label>
            <textarea name="message" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= $lang['defect_upload'] ?></label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100"><?= $lang['btn_send'] ?></button>
        </div>
    </div>
 </form>
</div>
</body>
</html>
