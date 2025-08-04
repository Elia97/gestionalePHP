#!/usr/bin/env php
<?php

/**
 * CLI per gestire l'ORM
 * 
 * Uso:
 *   php orm.php migrate        - Esegue le migrazioni
 *   php orm.php rollback       - Annulla l'ultima migrazione
 *   php orm.php seed           - Popola il database con dati di test
 *   php orm.php fresh          - Pulisce e ricrea il database
 *   php orm.php status         - Mostra lo stato delle migrazioni
 *   php orm.php stats          - Mostra statistiche del database
 */

require_once __DIR__ . '/orm/ORM.php';
require_once __DIR__ . '/orm/MigrationRunner.php';

// Verifica che sia fornito un comando
if ($argc < 2) {
    showHelp();
    exit(1);
}

$command = $argv[1];

try {
    switch ($command) {
        case 'migrate':
            $runner = new MigrationRunner();
            $runner->runMigrations();
            break;

        case 'rollback':
            echo "⚠️ Rollback non implementato per file SQL\n";
            echo "💡 Suggerimento: usa 'fresh' per pulire il database e 'migrate' per ricreare\n";
            break;

        case 'seed':
            $runner = new MigrationRunner();
            $runner->runSeeders();
            break;

        case 'fresh':
            $runner = new MigrationRunner();
            $runner->cleanDatabase();
            break;

        case 'fresh:seed':
            $runner = new MigrationRunner();
            $runner->cleanDatabase();
            $runner->runMigrations();
            $runner->runSeeders();
            break;

        case 'status':
            $runner = new MigrationRunner();
            $runner->migrationStatus();
            break;

        case 'stats':
            ORM::stats();
            break;

        case 'init':
            ORM::init();
            echo "✅ ORM inizializzato\n";
            break;

        case 'make:migration':
            if ($argc < 3) {
                echo "❌ Errore: Specifica il nome della migrazione\n";
                echo "Uso: php orm.php make:migration nome_migrazione\n";
                exit(1);
            }
            $runner = new MigrationRunner();
            $runner->createMigration($argv[2]);
            break;

        case 'make:seeder':
            if ($argc < 3) {
                echo "❌ Errore: Specifica il nome del seeder\n";
                echo "Uso: php orm.php make:seeder nome_seeder\n";
                exit(1);
            }
            $runner = new MigrationRunner();
            $runner->createSeeder($argv[2]);
            break;

        case 'help':
        case '--help':
        case '-h':
            showHelp();
            break;

        default:
            echo "❌ Comando non riconosciuto: $command\n\n";
            showHelp();
            exit(1);
    }
} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "\n";

    if (APP_DEBUG) {
        echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    }

    exit(1);
}

function showHelp(): void
{
    echo "🔧 Gestionale PHP - ORM CLI (SQL Server)\n\n";
    echo "Uso: php orm.php [comando]\n\n";
    echo "Comandi disponibili:\n";
    echo "  migrate              Esegue le migrazioni SQL dalla cartella orm/migrations\n";
    echo "  rollback             Annulla l'ultima migrazione (non implementato per SQL files)\n";
    echo "  seed                 Esegue i seeder SQL dalla cartella orm/seeders\n";
    echo "  fresh                Pulisce il database (ATTENZIONE: cancella tutto!)\n";
    echo "  fresh:seed           Pulisce, migra e popola il database\n";
    echo "  status               Mostra lo stato delle migrazioni\n";
    echo "  stats                Mostra statistiche del database\n";
    echo "  init                 Inizializza l'ORM e testa la connessione\n";
    echo "  make:migration <nome> Crea un nuovo file di migrazione\n";
    echo "  make:seeder <nome>   Crea un nuovo file seeder\n";
    echo "  help                 Mostra questo messaggio di aiuto\n\n";
    echo "Esempi:\n";
    echo "  php orm.php migrate                    # Esegue tutte le migrazioni SQL in ordine\n";
    echo "  php orm.php seed                       # Esegue tutti i seeder SQL\n";
    echo "  php orm.php fresh:seed                 # Setup completo da zero\n";
    echo "  php orm.php make:migration create_posts_table  # Crea migrazione per tabella posts\n";
    echo "  php orm.php make:seeder posts_seeder   # Crea seeder per la tabella posts\n\n";
    echo "Note:\n";
    echo "  - Le migrazioni vengono eseguite in ordine numerico\n";
    echo "  - Supporta SQL Server con sintassi nativa\n";
    echo "  - I file SQL vengono divisi automaticamente per statement\n\n";
}
