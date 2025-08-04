<?php

/**
 * Bootstrap ORM - Inizializza l'ORM e carica i modelli per SQL Server
 */

// Carica la configurazione
require_once __DIR__ . '/../config.php';

// Carica le classi principali dell'ORM
require_once __DIR__ . '/DatabaseManager.php';
require_once __DIR__ . '/QueryBuilder.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/MigrationRunner.php';

// Carica i modelli
require_once __DIR__ . '/Models/User.php';

/**
 * Classe ORM principale - Punto di accesso all'ORM per SQL Server
 */
class ORM
{
    private static bool $initialized = false;

    /**
     * Inizializza l'ORM
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        try {
            // Testa la connessione al database
            DatabaseManager::getConnection();
            echo "✅ ORM inizializzato correttamente per SQL Server\n";
            self::$initialized = true;
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "❌ Errore nell'inizializzazione ORM: {$errorMessage}\n";
            throw $e;
        }
    }

    /**
     * Ottiene statistiche del database
     */
    public static function stats(): void
    {
        self::init();
        echo "📊 Statistiche Database SQL Server:\n\n";

        $tables = [
            'users' => 'Utenti',
            'customers' => 'Clienti',
            'warehouses' => 'Magazzini',
            'products' => 'Prodotti',
            'stocks' => 'Scorte',
            'orders' => 'Ordini',
            'order_items' => 'Righe Ordine'
        ];

        foreach ($tables as $table => $label) {
            try {
                if (DatabaseManager::tableExists($table)) {
                    $count = DatabaseManager::fetchValue("SELECT COUNT(*) FROM [$table]");
                    echo sprintf("%-20s %s\n", $label . ':', $count ?? 0);
                } else {
                    echo sprintf("%-20s %s\n", "{$label}:", 'Tabella non esiste');
                }
            } catch (Exception $e) {
                $errorMsg = $e->getMessage();
                echo sprintf("%-20s %s\n", "{$label}:", "Errore: {$errorMsg}");
            }
        }

        echo "\n";
    }

    /**
     * Mostra informazioni sulla connessione
     */
    public static function info(): void
    {
        self::init();
        echo "ℹ️ Informazioni ORM:\n\n";

        try {
            $db = DatabaseManager::getConnection();
            $version = DatabaseManager::fetchValue("SELECT @@VERSION");
            echo "🔌 Database: SQL Server\n";
            echo "📡 Connessione: Attiva\n";
            echo "🏷️ Versione: " . (substr($version, 0, 50) ?? 'Non disponibile') . "...\n";

            // Lista tutte le tabelle
            $tables = DatabaseManager::fetchAll("
                SELECT TABLE_NAME 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_TYPE = 'BASE TABLE' 
                AND TABLE_CATALOG = DB_NAME()
                ORDER BY TABLE_NAME
            ");

            echo "\n📋 Tabelle disponibili (" . count($tables) . "):\n";
            foreach ($tables as $table) {
                echo "  📄 {$table['TABLE_NAME']}\n";
            }
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "❌ Errore nel recupero informazioni: {$errorMessage}\n";
        }

        echo "\n";
    }

    /**
     * Test rapido dell'ORM
     */
    public static function test(): void
    {
        self::init();
        echo "🧪 Test rapido dell'ORM:\n\n";

        try {
            // Test connessione
            echo "1. ✅ Connessione database: OK\n";

            // Test query di base
            $userCount = User::query()->count();
            echo "2. ✅ Query count utenti: $userCount\n";

            // Test creazione utente (solo se la tabella esiste e è vuota per test)
            if (DatabaseManager::tableExists('users')) {
                echo "3. ✅ Tabella users: Esiste\n";

                // Test query semplice
                $firstUser = User::query()->first();
                if ($firstUser) {
                    echo "4. ✅ Query primo utente: OK (ID: {$firstUser['id']})\n";
                } else {
                    echo "4. ℹ️ Nessun utente trovato (tabella vuota)\n";
                }
            } else {
                echo "3. ⚠️ Tabella users: Non esiste\n";
            }

            echo "\n🎉 Test completati con successo!\n";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "❌ Errore durante i test: {$errorMessage}\n";
        }
    }

    /**
     * Verifica la configurazione dell'ORM
     */
    public static function check(): void
    {
        echo "🔍 Verifica configurazione ORM:\n\n";

        // Verifica file di configurazione
        $configFile = __DIR__ . '/../config.php';
        if (file_exists($configFile)) {
            echo "✅ File config.php: Trovato\n";
        } else {
            echo "❌ File config.php: Non trovato\n";
            return;
        }

        // Verifica costanti database
        $requiredConstants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
        foreach ($requiredConstants as $constant) {
            if (defined($constant)) {
                $value = constant($constant);
                if ($constant === 'DB_PASS') {
                    echo "✅ $constant: Configurato (nascosto)\n";
                } else {
                    echo "✅ $constant: $value\n";
                }
            } else {
                echo "❌ $constant: Non definito\n";
            }
        }

        // Verifica driver
        $driver = Env::get('DB_DRIVER', 'sqlsrv');
        echo "🔧 Driver database: $driver\n";

        // Verifica estensione PDO
        if (extension_loaded('pdo_sqlsrv')) {
            echo "✅ Estensione PDO SQL Server: Caricata\n";
        } else {
            echo "❌ Estensione PDO SQL Server: Non caricata\n";
        }

        // Test connessione
        echo "\n🔌 Test connessione:\n";
        try {
            self::init();
            echo "✅ Connessione: Successo\n";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "❌ Connessione: Fallita - {$errorMessage}\n";
        }

        echo "\n";
    }
}
