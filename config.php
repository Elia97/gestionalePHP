<?php

/**
 * Configurazione applicazione
 * Carica variabili d'ambiente e definisce costanti globali
 */

require_once 'env_loader.php';

// Carica il file .env
Env::load();

// Definisci costanti per il database
define('DB_HOST', Env::get('DB_HOST', 'DB_HOST'));
define('DB_NAME', Env::get('DB_NAME', 'DB_NAME'));
define('DB_USER', Env::get('DB_USER', 'DB_USER'));
define('DB_PASS', Env::get('DB_PASS', 'DB_PASS'));

// Definisci costanti per l'applicazione
define('APP_NAME', Env::get('APP_NAME', 'APP_NAME'));
define('APP_URL', Env::get('APP_URL', 'APP_URL'));
define('APP_DEBUG', filter_var(Env::get('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN));
define('APP_ENV', Env::get('APP_ENV', 'development'));

// Definisci costanti per mail (se necessario)
define('MAIL_HOST', Env::get('MAIL_HOST', 'MAIL_HOST'));
define('MAIL_PORT', Env::get('MAIL_PORT', 587));
define('MAIL_USERNAME', Env::get('MAIL_USERNAME', 'MAIL_USERNAME'));
define('MAIL_PASSWORD', Env::get('MAIL_PASSWORD', 'MAIL_PASSWORD'));

// Definisci costanti per API (se necessario)
define('API_KEY', Env::get('API_KEY', 'API_KEY'));
define('SECRET_KEY', Env::get('SECRET_KEY', 'SECRET_KEY'));

// Debug: mostra configurazione se in modalità debug
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Mostra info di configurazione (solo in development)
    if (APP_ENV === 'development' && php_sapi_name() === 'cli') {
        echo "🔧 Configurazione caricata:\n";
        echo "- Database: " . DB_USER . "@" . DB_HOST . "/" . DB_NAME . "\n";
        echo "- App: " . APP_NAME . " (" . APP_ENV . ")\n";
        echo "- URL: " . APP_URL . "\n";
        echo "- Debug: " . (APP_DEBUG ? 'ON' : 'OFF') . "\n\n";
    }
}
