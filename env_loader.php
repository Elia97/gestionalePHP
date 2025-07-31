<?php
class Env
{
    private static $loaded = false;

    /**
     * Carica variabili dal file .env
     * 
     * @param string $path Percorso del file .env
     * @return void
     */
    public static function load($path = '.env')
    {
        // Evita di caricare più volte
        if (self::$loaded) {
            return;
        }

        if (!file_exists($path)) {
            // Crea un file .env di esempio se non esiste
            self::createExampleEnv($path);
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignora commenti e righe vuote
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Rimuovi quotes se presenti
                $value = self::removeQuotes($value);

                // Sostituzioni variabili ${VAR}
                $value = self::expandVariables($value);

                // Imposta la variabile d'ambiente
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }

        self::$loaded = true;
    }

    /**
     * Ottieni valore di una variabile d'ambiente
     * 
     * @param string $key Nome della variabile
     * @param mixed $default Valore di default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Rimuove quotes da una stringa
     */
    private static function removeQuotes($value)
    {
        $value = trim($value);

        // Rimuovi quotes singole o doppie
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Espande variabili ${VAR} nel valore
     */
    private static function expandVariables($value)
    {
        return preg_replace_callback('/\$\{([^}]+)\}/', function ($matches) {
            return $_ENV[$matches[1]] ?? $matches[0];
        }, $value);
    }

    /**
     * Crea un file .env di esempio
     */
    private static function createExampleEnv($path)
    {
        $example = '# Configurazione Database
DB_HOST=localhost
DB_NAME=mes_database
DB_USER=root
DB_PASS=

# Configurazione Applicazione
APP_NAME="MES PHP"
APP_URL=http://localhost:8000
APP_DEBUG=true
APP_ENV=development

# Configurazione Mail (opzionale)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=

# Chiavi API (opzionale)
API_KEY=
SECRET_KEY=
';

        file_put_contents($path, $example);
        echo "File .env creato automaticamente in: $path\n";
        echo "Modifica i valori secondo le tue necessità.\n";
    }
}
