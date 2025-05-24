<?php
// defekt-status.php
require_once "admin/db.php";
require_once "lang/lang.php";

// Token prüfen
$token = $_GET['token'] ?? '';
if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    die("Ungültiger Zugriff.");
}

// Defektmeldung laden
$stmt = $pdo->prepare("SELECT * FROM defects WHERE access_token = ?");
$stmt->execute([$token]);
$defect = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$defect) {
    die("Meldung nicht gefunden.");
}

// Nachrichtenverlauf zur Meldung laden
$stmtMsgs = $pdo->prepare("
    SELECT m.message, m.created_at, m.user_id, u.first_name, u.last_name
    FROM defect_messages m
    LEFT JOIN users u ON m.user_id = u.id
    WHERE m.defect_id = ?
    ORDER BY m.created_at ASC
");

$stmtMsgs->execute([$defect["id"]]);
$messages = $stmtMsgs->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Defektstatus</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div style="max-width:600px; margin:40px auto; font-family:sans-serif;">
    <h2>Status deiner Meldung</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($defect['name']) ?></p>
    <p><strong>E-Mail:</strong> <?= htmlspecialchars($defect['email']) ?></p>
    <p><strong>Duft:</strong> <?= htmlspecialchars($defect['duft']) ?></p>
    <p><strong>Problem:</strong> <?= htmlspecialchars($defect['problem']) ?></p>

    <?php if (!empty($defect["image"]) && file_exists(__DIR__ . "/uploads/defekte/" . $defect["image"])): ?>
        <p><strong>Bild:</strong><br>
        <img src="uploads/defekte/<?= htmlspecialchars($defect["image"]) ?>" alt="Bild"
             style="max-width:100%; border-radius:8px;"></p>
    <?php endif; ?>

    <p><strong>Nachricht:</strong><br>
    <pre style="white-space: pre-wrap;"><?= htmlspecialchars($defect['message']) ?></pre></p>

    <p><strong>Status:</strong> <?= $defect['is_done'] ? '✅ Erledigt' : '⏳ In Bearbeitung' ?></p>
    <?php if ($messages): ?>
    <hr>
    <h3>Antworten</h3>
    <div style="background:#f8f9fa; padding:15px; border-radius:6px;">
        <?php foreach ($messages as $m): ?>
            <div style="margin-bottom: 15px;">
                <div style="color:#666; font-size: 0.9em;">
                    <?= date("d.m.Y H:i", strtotime($m["created_at"])) ?> · <?= $m["user_id"] ? htmlspecialchars($m["first_name"] . ' ' . $m["last_name"]) : 'Kunde' ?>

                </div>
                <div style="white-space: pre-wrap; margin-top: 5px;">
                    <?= htmlspecialchars($m["message"]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<hr>
<h3>Antwort senden</h3>
<form method="post" action="reply_from_customer.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <textarea name="message" class="form-control" rows="4" required placeholder="Deine Antwort ..."></textarea>
    <button type="submit" class="btn btn-primary mt-2">Antwort absenden</button>
</form>

  </div>
</body>
</html>
