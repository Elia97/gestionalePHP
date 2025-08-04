<?php

/**
 * Migration Runner - Esegue le migrazioni SQL esistenti
 */

require_once __DIR__ . '/DatabaseManager.php';

class MigrationRunner
{
    private string $migrationsPath;
    private string $seedersPath;

    public function __construct()
    {
        $this->migrationsPath = __DIR__ . '/migrations/';
        $this->seedersPath = __DIR__ . '/seeders/';
    }

    /**
     * Esegue tutte le migrazioni in ordine
     */
    public function runMigrations(): void
    {
        echo "🔄 Esecuzione migrazioni SQL...\n\n";

        $this->createMigrationsTable();

        $migrationFiles = $this->getMigrationFiles();

        foreach ($migrationFiles as $file) {
            $fileName = basename($file);

            if (!$this->hasMigrationRun($fileName)) {
                $this->runMigration($file);
                $this->recordMigration($fileName);
            } else {
                echo "⏭️  Migrazione '$fileName' già eseguita\n";
            }
        }

        echo "\n✅ Tutte le migrazioni completate!\n";
    }

    /**
     * Esegue i seeder
     */
    public function runSeeders(): void
    {
        echo "🌱 Esecuzione seeder SQL...\n\n";

        $seederFiles = $this->getSeederFiles();

        foreach ($seederFiles as $file) {
            $this->runSeeder($file);
        }

        echo "\n✅ Tutti i seeder completati!\n";
    }

    /**
     * Pulisce il database (elimina tutte le tabelle)
     */
    public function cleanDatabase(): void
    {
        echo "🧹 Pulizia database...\n";

        try {
            $db = DatabaseManager::getConnection();

            // Ottieni tutte le tabelle
            $tables = $this->getAllTables();

            if (empty($tables)) {
                echo "ℹ️  Nessuna tabella da eliminare\n";
                return;
            }

            echo "📋 Trovate " . count($tables) . " tabelle da eliminare:\n";

            // Disabilita i vincoli foreign key (per SQL Server)
            $db->exec("EXEC sp_MSforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT ALL'");

            // Elimina ogni tabella
            foreach ($tables as $table) {
                echo "  🗑️  Eliminando tabella: {$table['TABLE_NAME']}\n";
                $db->exec("DROP TABLE [{$table['TABLE_NAME']}]");
            }

            echo "✅ Database pulito con successo!\n";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "❌ Errore durante la pulizia: {$errorMessage}\n";
            throw $e;
        }
    }

    /**
     * Ottiene tutti i file di migrazione ordinati
     */
    private function getMigrationFiles(): array
    {
        $files = glob($this->migrationsPath . '*.sql');
        sort($files); // Ordina per nome (che include il numero)
        return $files;
    }

    /**
     * Ottiene tutti i file di seeder ordinati
     */
    private function getSeederFiles(): array
    {
        $files = glob($this->seedersPath . '*.sql');
        sort($files); // Ordina per nome (che include il numero)
        return $files;
    }

    /**
     * Esegue una singola migrazione
     */
    private function runMigration(string $filePath): void
    {
        $fileName = basename($filePath);
        echo "📄 Eseguendo migrazione: $fileName\n";

        try {
            $sql = file_get_contents($filePath);
            $this->executeSQL($sql);
            echo "  ✅ Completata\n";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "  ❌ Errore: {$errorMessage}\n";
            throw $e;
        }
    }

    /**
     * Esegue un singolo seeder
     */
    private function runSeeder(string $filePath): void
    {
        $fileName = basename($filePath);
        echo "🌱 Eseguendo seeder: $fileName\n";

        try {
            $sql = file_get_contents($filePath);
            $this->executeSQL($sql);
            echo "  ✅ Completato\n";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "  ❌ Errore: {$errorMessage}\n";
            throw $e;
        }
    }

    /**
     * Esegue SQL dividendolo in statement separati
     */
    private function executeSQL(string $sql): void
    {
        $statements = $this->parseStatements($sql);
        $db = DatabaseManager::getConnection();

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $db->exec($statement);
            }
        }
    }

    /**
     * Divide il SQL in singoli statement
     */
    private function parseStatements(string $sql): array
    {
        // Rimuove commenti SQL su linea singola
        $sql = preg_replace('/--.*$/m', '', $sql);

        // Rimuove commenti SQL multi-linea
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // Per SQL Server, separa per GO (se presente) o per ';'
        $statements = stripos($sql, 'GO') !== false
            ? preg_split('/^\s*GO\s*$/im', $sql, -1, PREG_SPLIT_NO_EMPTY)
            : $this->splitSQLStatements($sql);

        return array_filter(array_map('trim', $statements));
    }

    /**
     * Separa gli statement SQL in modo intelligente
     */
    private function splitSQLStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if (!$inString) {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === ';') {
                    $statements[] = $current;
                    $current = '';
                    continue;
                }
            } else {
                if ($char === $stringChar) {
                    // Controlla se è un escape
                    if ($i + 1 < $length && $sql[$i + 1] === $stringChar) {
                        $current .= $char;
                        $i++; // Skip next character
                    } else {
                        $inString = false;
                    }
                }
            }

            $current .= $char;
        }

        if (!empty(trim($current))) {
            $statements[] = $current;
        }

        return $statements;
    }

    /**
     * Crea la tabella per tracciare le migrazioni eseguite
     */
    private function createMigrationsTable(): void
    {
        if (!DatabaseManager::tableExists('migrations')) {
            $sql = "
                CREATE TABLE migrations (
                    id INT IDENTITY(1,1) PRIMARY KEY,
                    migration NVARCHAR(255) NOT NULL,
                    executed_at DATETIME DEFAULT GETDATE()
                )
            ";

            DatabaseManager::execute($sql);
            echo "✅ Tabella 'migrations' creata\n";
        }
    }

    /**
     * Controlla se una migrazione è già stata eseguita
     */
    private function hasMigrationRun(string $fileName): bool
    {
        $result = DatabaseManager::fetchOne(
            "SELECT id FROM migrations WHERE migration = ?",
            [$fileName]
        );

        return $result !== null;
    }

    /**
     * Registra l'esecuzione di una migrazione
     */
    private function recordMigration(string $fileName): void
    {
        DatabaseManager::execute(
            "INSERT INTO migrations (migration) VALUES (?)",
            [$fileName]
        );
    }

    /**
     * Ottiene tutte le tabelle del database
     */
    private function getAllTables(): array
    {
        return DatabaseManager::fetchAll(
            "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG = DB_NAME()"
        );
    }

    /**
     * Mostra lo stato delle migrazioni
     */
    public function migrationStatus(): void
    {
        echo "📊 Stato delle migrazioni:\n\n";

        $this->createMigrationsTable();
        $migrationFiles = $this->getMigrationFiles();

        foreach ($migrationFiles as $file) {
            $fileName = basename($file);
            $status = $this->hasMigrationRun($fileName) ? '✅' : '⏳';
            echo "$status $fileName\n";
        }

        echo "\n";
    }

    /**
     * Crea un nuovo file di migrazione
     */
    public function createMigration(string $name): void
    {
        $timestamp = $this->getNextMigrationNumber();
        $fileName = sprintf("%02d-%s.sql", $timestamp, $this->sanitizeName($name));
        $filePath = $this->migrationsPath . $fileName;

        if (file_exists($filePath)) {
            echo "❌ Errore: Migrazione '$fileName' esiste già\n";
            return;
        }

        $template = $this->getMigrationTemplate($name);

        if (file_put_contents($filePath, $template) !== false) {
            echo "✅ Migrazione creata: $fileName\n";
            echo "📁 Percorso: $filePath\n";
            echo "💡 Modifica il file per definire la struttura della tabella\n";
        } else {
            echo "❌ Errore: Impossibile creare il file di migrazione\n";
        }
    }

    /**
     * Crea un nuovo file seeder
     */
    public function createSeeder(string $name): void
    {
        $timestamp = $this->getNextSeederNumber();
        $fileName = sprintf("%02d-%s.sql", $timestamp, $this->sanitizeName($name));
        $filePath = $this->seedersPath . $fileName;

        if (file_exists($filePath)) {
            echo "❌ Errore: Seeder '$fileName' esiste già\n";
            return;
        }

        $template = $this->getSeederTemplate($name);

        if (file_put_contents($filePath, $template) !== false) {
            echo "✅ Seeder creato: $fileName\n";
            echo "📁 Percorso: $filePath\n";
            echo "💡 Modifica il file per inserire i dati di esempio\n";
        } else {
            echo "❌ Errore: Impossibile creare il file seeder\n";
        }
    }

    /**
     * Ottiene il prossimo numero di migrazione
     */
    private function getNextMigrationNumber(): int
    {
        $files = $this->getMigrationFiles();
        if (empty($files)) {
            return 1;
        }

        $lastFile = basename(end($files));
        $number = (int) substr($lastFile, 0, 2);
        return $number + 1;
    }

    /**
     * Ottiene il prossimo numero di seeder
     */
    private function getNextSeederNumber(): int
    {
        $files = $this->getSeederFiles();
        if (empty($files)) {
            return 1;
        }

        $lastFile = basename(end($files));
        $number = (int) substr($lastFile, 0, 2);
        return $number + 1;
    }

    /**
     * Sanitizza il nome per il file
     */
    private function sanitizeName(string $name): string
    {
        // Converte in lowercase e sostituisce spazi/caratteri speciali con underscore
        $name = strtolower($name);
        $name = preg_replace('/[^a-z0-9_]/', '_', $name);
        $name = preg_replace('/_+/', '_', $name); // Rimuove underscore multipli
        return trim($name, '_');
    }

    /**
     * Template per le migrazioni
     */
    private function getMigrationTemplate(string $name): string
    {
        $tableName = $this->guessTableName($name);
        $comment = "-- Migrazione: " . ucfirst(str_replace('_', ' ', $name));
        $dateTime = date('Y-m-d H:i:s');

        return <<<SQL
-- ================================================
-- $comment
-- Creata: $dateTime
-- ================================================

-- Crea la tabella $tableName
CREATE TABLE [$tableName] (
    id INT IDENTITY(1,1) PRIMARY KEY,
    
    -- Aggiungi qui le colonne della tabella
    -- Esempio:
    -- name NVARCHAR(255) NOT NULL,
    -- email NVARCHAR(255) UNIQUE,
    -- created_at DATETIME DEFAULT GETDATE(),
    -- updated_at DATETIME DEFAULT GETDATE()
);

-- Indici (opzionale)
-- CREATE INDEX IX_{$tableName}_name ON [$tableName] (name);

-- Commenti tabella (opzionale)
-- EXEC sp_addextendedproperty 
--     @name = N'MS_Description', 
--     @value = N'Descrizione della tabella $tableName',
--     @level0type = N'SCHEMA', @level0name = N'dbo',
--     @level1type = N'TABLE', @level1name = N'$tableName';

SQL;
    }

    /**
     * Template per i seeder
     */
    private function getSeederTemplate(string $name): string
    {
        $tableName = $this->guessTableNameForSeeder($name);
        $comment = "-- Seeder: " . ucfirst(str_replace('_', ' ', $name));
        $dateTime = date('Y-m-d H:i:s');

        return <<<SQL
-- ================================================
-- $comment
-- Creato: $dateTime
-- ================================================

-- Popola la tabella $tableName con dati di esempio

-- Esempio di inserimento dati:
INSERT INTO [$tableName] (name, email, created_at) VALUES
    ('Mario Rossi', 'mario.rossi@example.com', GETDATE()),
    ('Luigi Bianchi', 'luigi.bianchi@example.com', GETDATE()),
    ('Anna Verdi', 'anna.verdi@example.com', GETDATE());

-- Oppure inserimenti singoli:
-- INSERT INTO [$tableName] (name, email) VALUES ('Nome Utente', 'email@example.com');

-- Per dati più complessi, puoi usare:
-- DECLARE @userId INT;
-- INSERT INTO [$tableName] (name) VALUES ('Test User');
-- SET @userId = SCOPE_IDENTITY();
-- 
-- INSERT INTO [altra_tabella] (user_id, data) VALUES (@userId, 'Dati correlati');

SQL;
    }

    /**
     * Cerca di indovinare il nome della tabella dal nome della migrazione
     */
    private function guessTableName(string $name): string
    {
        // Cerca pattern comuni
        if (preg_match('/create[_\s]*(.+)[_\s]*table/i', $name, $matches)) {
            return $this->sanitizeName($matches[1]);
        }

        if (preg_match('/add[_\s]*(.+)[_\s]*to[_\s]*(.+)/i', $name, $matches)) {
            return $this->sanitizeName($matches[2]);
        }

        if (preg_match('/modify[_\s]*(.+)[_\s]*table/i', $name, $matches)) {
            return $this->sanitizeName($matches[1]);
        }

        // Se non trova pattern, usa il nome sanitizzato
        return $this->sanitizeName($name);
    }

    /**
     * Cerca di indovinare il nome della tabella dal nome del seeder
     */
    private function guessTableNameForSeeder(string $name): string
    {
        // Rimuove "seeder" dal nome se presente
        $name = preg_replace('/_?seeder$/i', '', $name);

        // Se il nome è plurale, lo mantiene, altrimenti lo pluralizza
        if (!preg_match('/s$/i', $name)) {
            $name .= 's';
        }

        return $this->sanitizeName($name);
    }
}
