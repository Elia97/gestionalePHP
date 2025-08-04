<?php

/**
 * Model Base - Classe astratta per tutti i modelli ORM
 */

require_once __DIR__ . '/DatabaseManager.php';
require_once __DIR__ . '/QueryBuilder.php';

abstract class Model
{
    // Proprietà che devono essere definite nelle classi figlie
    protected static string $tableName;
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $hidden = [];
    protected static array $casts = [];

    // Proprietà dell'istanza
    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Riempie il modello con attributi
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (empty(static::$fillable) || in_array($key, static::$fillable)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    /**
     * Imposta un attributo
     */
    public function setAttribute(string $key, $value): void
    {
        // Applica casting se definito
        if (isset(static::$casts[$key])) {
            $value = $this->castAttribute($key, $value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Ottiene un attributo
     */
    public function getAttribute(string $key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }

        $value = $this->attributes[$key];

        // Applica casting se definito
        if (isset(static::$casts[$key])) {
            return $this->castAttribute($key, $value);
        }

        return $value;
    }

    /**
     * Applica il casting a un attributo
     */
    protected function castAttribute(string $key, $value)
    {
        $cast = static::$casts[$key];

        if ($value === null) {
            return null;
        }

        switch ($cast) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'string':
                return (string) $value;
            case 'array':
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            case 'date':
            case 'datetime':
                return is_string($value) ? new DateTime($value) : $value;
            default:
                return $value;
        }
    }

    /**
     * Magic getter
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Magic setter
     */
    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }

    /**
     * Magic isset
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Controlla se il modello esiste nel database
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Ottiene la chiave primaria
     */
    public function getKey()
    {
        return $this->getAttribute(static::$primaryKey);
    }

    /**
     * Salva il modello nel database
     */
    public function save(): bool
    {
        try {
            return $this->exists ? $this->performUpdate() : $this->performInsert();
        } catch (Exception $e) {
            if (APP_DEBUG) {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Esegue l'inserimento
     */
    protected function performInsert(): bool
    {
        $data = $this->getAttributesForSave();

        if (empty($data)) {
            return true;
        }

        $query = new QueryBuilder(static::$tableName);
        $sql = $query->buildInsert($data);

        DatabaseManager::execute($sql, $data);

        // Se la tabella ha un ID auto-incrementale, lo impostiamo
        if (static::$primaryKey === 'id' && !isset($data['id'])) {
            $this->setAttribute('id', (int) DatabaseManager::lastInsertId());
        }

        $this->exists = true;
        $this->original = $this->attributes;

        return true;
    }

    /**
     * Esegue l'aggiornamento
     */
    protected function performUpdate(): bool
    {
        $data = $this->getAttributesForSave();

        if (empty($data)) {
            return true;
        }

        $query = new QueryBuilder(static::$tableName);
        $query->where(static::$primaryKey, '=', $this->getKey());

        $sql = $query->buildUpdate($data);
        $params = array_merge($data, $query->getParams());

        DatabaseManager::execute($sql, $params);

        $this->original = $this->attributes;

        return true;
    }

    /**
     * Ottiene gli attributi da salvare (esclude hidden e non modificati)
     */
    protected function getAttributesForSave(): array
    {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            // Salta gli attributi nascosti
            if (in_array($key, static::$hidden)) {
                continue;
            }

            // Per gli update, salta gli attributi non modificati
            if ($this->exists && isset($this->original[$key]) && $this->original[$key] === $value) {
                continue;
            }

            // Prepara il valore per il database
            if (isset(static::$casts[$key])) {
                $value = $this->prepareForDatabase($key, $value);
            }

            $attributes[$key] = $value;
        }

        return $attributes;
    }

    /**
     * Prepara un valore per il salvataggio nel database
     */
    protected function prepareForDatabase(string $key, $value)
    {
        $cast = static::$casts[$key];

        switch ($cast) {
            case 'array':
            case 'json':
                return json_encode($value);
            case 'date':
            case 'datetime':
                if ($value instanceof DateTime) {
                    return $value->format('Y-m-d H:i:s');
                }
                return $value;
            default:
                return $value;
        }
    }

    /**
     * Elimina il modello dal database
     */
    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }

        try {
            $query = new QueryBuilder(static::$tableName);
            $query->where(static::$primaryKey, '=', $this->getKey());

            $sql = $query->buildDelete();
            DatabaseManager::execute($sql, $query->getParams());

            $this->exists = false;

            return true;
        } catch (Exception $e) {
            if (APP_DEBUG) {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Converte il modello in array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, static::$hidden)) {
                $array[$key] = $this->getAttribute($key);
            }
        }

        return $array;
    }

    /**
     * Converte il modello in JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    // ================================
    // METODI STATICI PER QUERY
    // ================================

    /**
     * Inizia una nuova query
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::$tableName);
    }

    /**
     * Trova un record per ID
     */
    public static function find($id): ?static
    {
        $result = static::query()
            ->where(static::$primaryKey, '=', $id)
            ->first();

        if ($result) {
            return static::newFromDatabase($result);
        }

        return null;
    }

    /**
     * Trova un record per ID o solleva eccezione
     */
    public static function findOrFail($id): static
    {
        $model = static::find($id);

        if (!$model) {
            throw new Exception("Modello non trovato con ID: $id");
        }

        return $model;
    }

    /**
     * Ottiene tutti i record
     */
    public static function all(): array
    {
        $results = static::query()->get();
        return array_map([static::class, 'newFromDatabase'], $results);
    }

    /**
     * Trova il primo record che soddisfa le condizioni
     */
    public static function where(string $column, string $operator, $value): QueryBuilder
    {
        return static::query()->where($column, $operator, $value);
    }

    /**
     * Crea un nuovo modello e lo salva
     */
    public static function create(array $attributes): static
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    /**
     * Crea una nuova istanza del modello dai dati del database
     */
    protected static function newFromDatabase(array $attributes): static
    {
        $model = new static();
        $model->attributes = $attributes;
        $model->original = $attributes;
        $model->exists = true;
        return $model;
    }

    /**
     * Ottiene il nome della tabella
     */
    public static function getTableName(): string
    {
        return static::$tableName;
    }
}
