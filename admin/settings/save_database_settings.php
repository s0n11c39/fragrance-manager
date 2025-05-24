<?php
session_start();
require_once __DIR__ . '/../alerts.php';

// Dynamischer Redirect zur settings.php mit Tab-Rücksprung
$baseUrl = dirname($_SERVER['SCRIPT_NAME']);
$redirectToSettings = $baseUrl . '/settings.php?tab=database_settings';

// Lade bestehende Verbindungsdaten
$configPath = __DIR__ . '/../config/db_config.json';
if (file_exists($configPath)) {
    $config = json_decode(file_get_contents($configPath), true);
    $host = $config['host'];
    $dbname = $config['dbname'];
    $user = $config['user'];
    $pass = $config['pass'];
} else {
    $host = "localhost";
    $dbname = "scent";
    $user = "root";
    $pass = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // BACKUP EXPORT
    if ($action === 'export') {
        $date = date('Y-m-d_H-i-s');
        $filename = "backup_{$dbname}_{$date}.sql";
        $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

        $command = "\"$mysqldumpPath\" --user=$user --password=$pass --host=$host $dbname";

        exec("$command 2>&1", $output, $returnVar);

        if ($returnVar === 0) {
            header('Content-Type: application/sql');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo implode("\n", $output);
            exit;
        } else {
            setAlert("error", "❌ Fehler beim Erstellen des Backups.");
            header("Location: $redirectToSettings");
            exit;
        }
    }

    // SPEICHER-AKTION
    if ($action === 'save_database_settings') {

        // 1. Backup importieren (wenn vorhanden)
        if (!empty($_FILES['import_file']['name'])) {
            $fileTmpPath = $_FILES['import_file']['tmp_name'];
            $fileName = $_FILES['import_file']['name'];

            if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'sql') {
                setAlert("error", "❌ Nur .sql-Dateien sind erlaubt.");
                header("Location: $redirectToSettings");
                exit;
            }

            $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';
            $tmpImportFile = __DIR__ . '/tmp_import.sql';
            move_uploaded_file($fileTmpPath, $tmpImportFile);

            $command = "\"$mysqlPath\" --user=$user --password=$pass --host=$host $dbname < \"$tmpImportFile\"";
            exec($command, $output, $returnVar);
            unlink($tmpImportFile);

            if ($returnVar === 0) {
                setAlert("success", "✅ Backup wurde erfolgreich importiert.");
            } else {
                setAlert("error", "❌ Backup-Import fehlgeschlagen.");
                header("Location: $redirectToSettings");
                exit;
            }
        }

        // 2. Verbindung speichern (wenn ausgefüllt)
        $newConfig = [
            'host'   => trim($_POST['db_host'] ?? ''),
            'dbname' => trim($_POST['db_name'] ?? ''),
            'user'   => trim($_POST['db_user'] ?? ''),
            'pass'   => trim($_POST['db_pass'] ?? '')
        ];

        if ($newConfig['host'] || $newConfig['dbname'] || $newConfig['user']) {
            try {
                $dsn = "mysql:host={$newConfig['host']};dbname={$newConfig['dbname']};charset=utf8mb4";
                $pdo = new PDO($dsn, $newConfig['user'], $newConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                setAlert("error", "❌ Verbindung fehlgeschlagen: " . $e->getMessage());
                header("Location: $redirectToSettings");
                exit;
            }

            if (!is_writable($configPath)) {
                setAlert("error", "❌ Die Datei <code>db_config.json</code> ist nicht beschreibbar.");
                header("Location: $redirectToSettings");
                exit;
            }

            if (file_put_contents($configPath, json_encode($newConfig, JSON_PRETTY_PRINT))) {
                setAlert("success", "✅ Verbindung erfolgreich getestet und gespeichert.");
            } else {
                setAlert("error", "❌ Fehler beim Speichern der Verbindungsdaten.");
            }
        }

        header("Location: $redirectToSettings");
        exit;
    }

    setAlert("error", "❌ Unbekannte Aktion.");
    header("Location: $redirectToSettings");
    exit;
}

http_response_code(403);
exit('❌ Direkter Zugriff nicht erlaubt.');
