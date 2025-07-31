<?php

/**
 * Connessione al database usando variabili d'ambiente
 */

require_once 'config.php';

try {
    // Costruisci DSN basato sul tipo di database
    $driver = Env::get('DB_DRIVER', 'mysql');

    switch ($driver) {
        case 'sqlsrv':
            // SQL Server
            $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME . ";Encrypt=yes;TrustServerCertificate=yes";
            break;

        case 'mysql':
        default:
            // MySQL (default)
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            break;
    }

    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Evita prepared statement emulati per migliori performance
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    if (APP_DEBUG) {
        die("❌ Connessione al database fallita: " . $e->getMessage());
    } else {
        die("❌ Errore di connessione al database. Controlla la configurazione.");
    }
}
