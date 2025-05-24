<?php
$host = $_POST['db_host'] ?? '';
$name = $_POST['db_name'] ?? '';
$user = $_POST['db_user'] ?? '';
$pass = $_POST['db_pass'] ?? '';

try {
    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo '<div class="text-success">Verbindung erfolgreich!</div>';
} catch (PDOException $e) {
    echo '<div class="text-danger">Verbindung fehlgeschlagen:<br>' . htmlspecialchars($e->getMessage()) . '</div>';
}
