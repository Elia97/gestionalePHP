<?php

/**
 * Model Product - Gestisce i prodotti del magazzino
 */

require_once __DIR__ . '/../Model.php';

class Product extends Model
{
    protected static string $tableName = 'products';
    protected static string $primaryKey = 'id';

    protected static array $fillable = [
        'nome',
        'codice',
        'descrizione',
        'prezzo',
        'costo',
        'categoria_id',
        'fornitore_id',
        'quantita_minima',
        'unita_misura',
        'attivo',
        'data_creazione'
    ];

    protected static array $casts = [
        'id' => 'int',
        'prezzo' => 'float',
        'costo' => 'float',
        'categoria_id' => 'int',
        'fornitore_id' => 'int',
        'quantita_minima' => 'int',
        'attivo' => 'bool',
        'data_creazione' => 'datetime'
    ];

    /**
     * Calcola il margine di guadagno
     */
    public function getMargine(): float
    {
        if ($this->costo <= 0) {
            return 0;
        }

        return (($this->prezzo - $this->costo) / $this->costo) * 100;
    }

    /**
     * Calcola il guadagno per unità
     */
    public function getGuadagno(): float
    {
        return $this->prezzo - $this->costo;
    }

    /**
     * Controlla se il prodotto è attivo
     */
    public function isAttivo(): bool
    {
        return (bool) $this->attivo;
    }

    /**
     * Ottiene il codice del prodotto formattato
     */
    public function getCodiceFormattato(): string
    {
        return strtoupper($this->codice);
    }

    /**
     * Controlla se il prezzo è valido
     */
    public function hasPrezzoValido(): bool
    {
        return $this->prezzo > 0;
    }

    /**
     * Scope per ottenere solo prodotti attivi
     */
    public static function attivi(): QueryBuilder
    {
        return static::query()->where('attivo', '=', 1);
    }

    /**
     * Scope per ottenere prodotti per categoria
     */
    public static function perCategoria(int $categoriaId): QueryBuilder
    {
        return static::query()->where('categoria_id', '=', $categoriaId);
    }

    /**
     * Scope per ottenere prodotti per fornitore
     */
    public static function perFornitore(int $fornitoreId): QueryBuilder
    {
        return static::query()->where('fornitore_id', '=', $fornitoreId);
    }

    /**
     * Scope per prodotti con scorte basse
     */
    public static function scorteBasse(): QueryBuilder
    {
        return static::query()
            ->select(['products.*', 'COALESCE(SUM(movements.quantita), 0) as scorte_attuali'])
            ->leftJoin('stock_movements movements', 'products.id = movements.product_id')
            ->groupBy('products.id')
            ->having('scorte_attuali <= products.quantita_minima');
    }

    /**
     * Trova un prodotto per codice
     */
    public static function findByCodice(string $codice): ?static
    {
        $result = static::query()
            ->where('codice', '=', strtoupper($codice))
            ->first();

        return $result ? static::newFromDatabase($result) : null;
    }

    /**
     * Cerca prodotti per nome, codice o descrizione
     */
    public static function search(string $termine): array
    {
        $results = static::query()
            ->where('nome', 'LIKE', "%$termine%")
            ->orWhere('codice', 'LIKE', "%$termine%")
            ->orWhere('descrizione', 'LIKE', "%$termine%")
            ->orderBy('nome')
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }

    /**
     * Ottiene i prodotti più venduti
     */
    public static function piuVenduti(int $limit = 10): array
    {
        $results = static::query()
            ->select(['products.*', 'SUM(order_items.quantita) as totale_venduto'])
            ->leftJoin('order_items', 'products.id = order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('totale_venduto', 'DESC')
            ->limit($limit)
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }

    /**
     * Ottiene i prodotti con il margine più alto
     */
    public static function margineAlto(int $limit = 10): array
    {
        $results = static::query()
            ->select(['*', '((prezzo - costo) / costo * 100) as margine_calcolato'])
            ->where('costo', '>', 0)
            ->orderBy('margine_calcolato', 'DESC')
            ->limit($limit)
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }
}
