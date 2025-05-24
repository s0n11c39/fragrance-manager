<?php
require_once "db.php";

$customerId = (int) ($_GET["id"] ?? 0);
if (!$customerId) {
    exit("Keine ID angegeben.");
}

$stmt = $pdo->prepare("SELECT * FROM defects WHERE customer_id = ? ORDER BY created_at DESC");
$stmt->execute([$customerId]);
$defects = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$defects) {
    echo "<p>Keine weiteren Meldungen gefunden.</p>";
    exit;
}

foreach ($defects as $d) {
    echo "<div style='margin-bottom:1rem;padding:0.5rem;border:1px solid #ccc;border-radius:5px'>";
    echo "<strong>" . htmlspecialchars($d['duft']) . "</strong> · " . date("d.m.Y H:i", strtotime($d['created_at'])) . "<br>";
    echo "<small><em>" . htmlspecialchars($d['problem']) . "</em></small><br>";
    echo nl2br(htmlspecialchars($d['message']));
    echo "</div>";
}
