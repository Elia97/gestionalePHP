<?php

/**
 * Query Builder - Costruisce query SQL in modo dinamico e fluente
 */

class QueryBuilder
{
    private string $table;
    private array $select = ['*'];
    private array $joins = [];
    private array $where = [];
    private array $groupBy = [];
    private array $having = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private int $offset = 0;
    private array $params = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Specifica i campi da selezionare
     */
    public function select(array $fields): self
    {
        $this->select = $fields;
        return $this;
    }

    /**
     * Aggiunge una condizione WHERE
     */
    public function where(string $column, string $operator, $value): self
    {
        $placeholder = ':param_' . count($this->params);
        $this->where[] = "$column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    /**
     * Aggiunge una condizione WHERE con AND
     */
    public function andWhere(string $column, string $operator, $value): self
    {
        return $this->where($column, $operator, $value);
    }

    /**
     * Aggiunge una condizione WHERE con OR
     */
    public function orWhere(string $column, string $operator, $value): self
    {
        $placeholder = ':param_' . count($this->params);
        $this->where[] = "OR $column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    /**
     * Aggiunge una condizione WHERE IN
     */
    public function whereIn(string $column, array $values): self
    {
        $placeholders = [];
        foreach ($values as $value) {
            $placeholder = ':param_' . count($this->params);
            $placeholders[] = $placeholder;
            $this->params[$placeholder] = $value;
        }
        $this->where[] = "$column IN (" . implode(', ', $placeholders) . ")";
        return $this;
    }

    /**
     * Aggiunge una condizione WHERE LIKE
     */
    public function whereLike(string $column, string $value): self
    {
        return $this->where($column, 'LIKE', $value);
    }

    /**
     * Aggiunge un JOIN
     */
    public function join(string $table, string $condition, string $type = 'INNER'): self
    {
        $this->joins[] = "$type JOIN $table ON $condition";
        return $this;
    }

    /**
     * Aggiunge un LEFT JOIN
     */
    public function leftJoin(string $table, string $condition): self
    {
        return $this->join($table, $condition, 'LEFT');
    }

    /**
     * Aggiunge un RIGHT JOIN
     */
    public function rightJoin(string $table, string $condition): self
    {
        return $this->join($table, $condition, 'RIGHT');
    }

    /**
     * Aggiunge GROUP BY
     */
    public function groupBy(string $column): self
    {
        $this->groupBy[] = $column;
        return $this;
    }

    /**
     * Aggiunge HAVING
     */
    public function having(string $condition): self
    {
        $this->having[] = $condition;
        return $this;
    }

    /**
     * Aggiunge ORDER BY
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    /**
     * Aggiunge LIMIT
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Aggiunge OFFSET
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Costruisce la query SELECT
     */
    public function buildSelect(): string
    {
        $driver = Env::get('DB_DRIVER', 'sqlsrv');

        // Per SQL Server, se abbiamo LIMIT senza ORDER BY, aggiungiamo un ORDER BY di default
        if ($driver === 'sqlsrv' && $this->limit !== null && empty($this->orderBy)) {
            $this->orderBy('1');
        }

        $sql = 'SELECT ';

        // SQL Server: usa TOP se abbiamo solo LIMIT (senza OFFSET)
        if ($driver === 'sqlsrv' && $this->limit !== null && $this->offset === 0) {
            $sql .= "TOP {$this->limit} ";
        }

        $sql .= implode(', ', $this->select);
        $sql .= " FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        if (!empty($this->having)) {
            $sql .= ' HAVING ' . implode(' AND ', $this->having);
        }

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        // Gestione LIMIT/OFFSET per diversi database
        if ($driver === 'mysql') {
            if ($this->limit !== null) {
                $sql .= " LIMIT {$this->limit}";
            }
            if ($this->offset > 0) {
                $sql .= " OFFSET {$this->offset}";
            }
        } elseif ($driver === 'sqlsrv') {
            // SQL Server: usa OFFSET/FETCH solo se abbiamo OFFSET > 0 o LIMIT con OFFSET
            if ($this->offset > 0 || ($this->limit !== null && $this->offset > 0)) {
                $sql .= " OFFSET {$this->offset} ROWS";
                if ($this->limit !== null) {
                    $sql .= " FETCH NEXT {$this->limit} ROWS ONLY";
                }
            }
        }

        return $sql;
    }

    /**
     * Costruisce la query INSERT
     */
    public function buildInsert(array $data): string
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $columnsStr = implode(', ', $columns);
        $placeholdersStr = implode(', ', $placeholders);
        $sql = "INSERT INTO {$this->table} ({$columnsStr}) VALUES ({$placeholdersStr})";

        return $sql;
    }

    /**
     * Costruisce la query UPDATE
     */
    public function buildUpdate(array $data): string
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "$column = :$column";
        }

        $setsStr = implode(', ', $sets);
        $sql = "UPDATE {$this->table} SET {$setsStr}";

        if (!empty($this->where)) {
            $whereStr = implode(' AND ', $this->where);
            $sql .= " WHERE {$whereStr}";
        }

        return $sql;
    }

    /**
     * Costruisce la query DELETE
     */
    public function buildDelete(): string
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        return $sql;
    }

    /**
     * Ottiene i parametri per la query
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Esegue la query SELECT e restituisce tutti i risultati
     */
    public function get(): array
    {
        return DatabaseManager::fetchAll($this->buildSelect(), $this->params);
    }

    /**
     * Esegue la query SELECT e restituisce il primo risultato
     */
    public function first(): ?array
    {
        $this->limit(1);
        return DatabaseManager::fetchOne($this->buildSelect(), $this->params);
    }

    /**
     * Conta i risultati
     */
    public function count(): int
    {
        $originalSelect = $this->select;
        $this->select = ['COUNT(*) as count'];
        $result = DatabaseManager::fetchValue($this->buildSelect(), $this->params);
        $this->select = $originalSelect;
        return (int) $result;
    }

    /**
     * Controlla se esistono risultati
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }
}
