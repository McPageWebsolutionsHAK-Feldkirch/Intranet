<?php
// Datenbankkonfiguration
$dbHost = 'intranet-intranet-50c3.i.aivencloud.com';  // Hostname oder IP-Adresse
$dbPort = '13714';                                   // Port
$dbName = 'Intranet_2025';                         // Ersetze durch deinen Datenbanknamen
$dbUser = 'avnadmin';                                // Benutzername
$dbPass = 'AVNS_mWfaaKVvqphvPbqTk_h';                // Ersetze durch das echte Passwort

// Verbindung zur Datenbank herstellen
try {
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    echo "Verbindung erfolgreich!";
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>
