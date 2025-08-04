<?php

/**
 * Database Manager - Gestisce connessioni, transazioni e operazioni di base
 */

class DatabaseManager
{
    private static ?PDO $connection = null;
    private static bool $inTransaction = false;

    /**
     * Ottiene la connessione al database (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            require_once __DIR__ . '/../config.php';

            try {
                $driver = Env::get('DB_DRIVER', 'sqlsrv');

                switch ($driver) {
                    case 'sqlsrv':
                        $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME . ";Encrypt=yes;TrustServerCertificate=yes";
                        break;
                    case 'mysql':
                    default:
                        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                        break;
                }

                self::$connection = new PDO($dsn, DB_USER, DB_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                if (APP_DEBUG) {
                    $errorMessage = $e->getMessage();
                    throw new Exception("❌ Connessione al database fallita: {$errorMessage}");
                } else {
                    throw new Exception("❌ Errore di connessione al database.");
                }
            }
        }

        return self::$connection;
    }

    /**
     * Esegue una query SQL con parametri
     */
    public static function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ottiene tutti i risultati di una query
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::execute($sql, $params)->fetchAll();
    }

    /**
     * Ottiene il primo risultato di una query
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $result = self::execute($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Ottiene un singolo valore
     */
    public static function fetchValue(string $sql, array $params = [])
    {
        $result = self::execute($sql, $params)->fetchColumn();
        return $result !== false ? $result : null;
    }

    /**
     * Inizia una transazione
     */
    public static function beginTransaction(): void
    {
        if (!self::$inTransaction) {
            self::getConnection()->beginTransaction();
            self::$inTransaction = true;
        }
    }

    /**
     * Conferma una transazione
     */
    public static function commit(): void
    {
        if (self::$inTransaction) {
            self::getConnection()->commit();
            self::$inTransaction = false;
        }
    }

    /**
     * Annulla una transazione
     */
    public static function rollback(): void
    {
        if (self::$inTransaction) {
            self::getConnection()->rollback();
            self::$inTransaction = false;
        }
    }

    /**
     * Esegue una funzione all'interno di una transazione
     */
    public static function transaction(callable $callback)
    {
        self::beginTransaction();

        try {
            $result = $callback();
            self::commit();
            return $result;
        } catch (Exception $e) {
            self::rollback();
            throw $e;
        }
    }

    /**
     * Ottiene l'ultimo ID inserito
     */
    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }

    /**
     * Controlla se una tabella esiste
     */
    public static function tableExists(string $tableName): bool
    {
        $driver = Env::get('DB_DRIVER', 'sqlsrv');

        $sql = $driver === 'mysql'
            ? "SHOW TABLES LIKE ?"
            : "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = ?";

        return self::fetchOne($sql, [$tableName]) !== null;
    }
    /**
     * Ottiene informazioni sulle colonne di una tabella
     */
    public static function getTableColumns(string $tableName): array
    {
        $driver = Env::get('DB_DRIVER', 'sqlsrv');

        [$sql, $params] = $driver === 'mysql'
            ? ["DESCRIBE `$tableName`", []]
            : ["SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?", [$tableName]];

        return self::fetchAll($sql, $params);
    }
}
