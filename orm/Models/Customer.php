<?php

/**
 * Model Customer - Gestisce i clienti
 */

require_once __DIR__ . '/../Model.php';

class Customer extends Model
{
    protected static string $tableName = 'customers';
    protected static string $primaryKey = 'id';

    protected static array $fillable = [
        'nome',
        'cognome',
        'ragione_sociale',
        'codice_fiscale',
        'partita_iva',
        'email',
        'telefono',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'nazione',
        'tipo_cliente',
        'sconto_default',
        'limite_credito',
        'attivo',
        'data_registrazione',
        'note'
    ];

    protected static array $casts = [
        'id' => 'int',
        'sconto_default' => 'float',
        'limite_credito' => 'float',
        'attivo' => 'bool',
        'data_registrazione' => 'datetime'
    ];

    // Costanti per il tipo di cliente
    public const string TIPO_PRIVATO = 'privato';
    public const string TIPO_AZIENDA = 'azienda';

    /**
     * Ottiene il nome da visualizzare (ragione sociale o nome/cognome)
     */
    public function getNomeDisplay(): string
    {
        if ($this->tipo_cliente === self::TIPO_AZIENDA && $this->ragione_sociale) {
            return $this->ragione_sociale;
        }

        return trim($this->nome . ' ' . $this->cognome);
    }

    /**
     * Ottiene l'indirizzo completo
     */
    public function getIndirizzoCompleto(): string
    {
        $parti = array_filter([
            $this->indirizzo,
            $this->cap . ' ' . $this->citta,
            $this->provincia ? "($this->provincia)" : null,
            $this->nazione !== 'Italia' ? $this->nazione : null
        ]);

        return implode(', ', $parti);
    }

    /**
     * Controlla se il cliente è attivo
     */
    public function isAttivo(): bool
    {
        return (bool) $this->attivo;
    }

    /**
     * Controlla se il cliente è un'azienda
     */
    public function isAzienda(): bool
    {
        return $this->tipo_cliente === self::TIPO_AZIENDA;
    }

    /**
     * Controlla se il cliente è privato
     */
    public function isPrivato(): bool
    {
        return $this->tipo_cliente === self::TIPO_PRIVATO;
    }

    /**
     * Ottiene lo sconto default come percentuale
     */
    public function getScontoPercentuale(): string
    {
        return number_format($this->sconto_default, 2) . '%';
    }

    /**
     * Controlla se ha un limite di credito impostato
     */
    public function hasLimiteCredito(): bool
    {
        return $this->limite_credito > 0;
    }

    /**
     * Calcola il credito utilizzato (da implementare con ordini)
     */
    public function getCreditoUtilizzato(): float
    {
        // Qui dovrebbe essere calcolato il totale degli ordini non pagati
        // Per ora restituiamo 0
        return 0.0;
    }

    /**
     * Calcola il credito disponibile
     */
    public function getCreditoDisponibile(): float
    {
        if (!$this->hasLimiteCredito()) {
            return PHP_FLOAT_MAX;
        }

        return max(0, $this->limite_credito - $this->getCreditoUtilizzato());
    }

    /**
     * Controlla se può effettuare un ordine di un certo importo
     */
    public function puoOrdinare(float $importo): bool
    {
        if (!$this->isAttivo()) {
            return false;
        }

        if (!$this->hasLimiteCredito()) {
            return true;
        }

        return $this->getCreditoDisponibile() >= $importo;
    }

    /**
     * Scope per ottenere solo clienti attivi
     */
    public static function attivi(): QueryBuilder
    {
        return static::query()->where('attivo', '=', 1);
    }

    /**
     * Scope per ottenere clienti per tipo
     */
    public static function perTipo(string $tipo): QueryBuilder
    {
        return static::query()->where('tipo_cliente', '=', $tipo);
    }

    /**
     * Scope per ottenere solo aziende
     */
    public static function aziende(): QueryBuilder
    {
        return static::perTipo(self::TIPO_AZIENDA);
    }

    /**
     * Scope per ottenere solo privati
     */
    public static function privati(): QueryBuilder
    {
        return static::perTipo(self::TIPO_PRIVATO);
    }

    /**
     * Scope per clienti con sconto
     */
    public static function conSconto(): QueryBuilder
    {
        return static::query()->where('sconto_default', '>', 0);
    }

    /**
     * Trova un cliente per email
     */
    public static function findByEmail(string $email): ?static
    {
        $result = static::query()
            ->where('email', '=', $email)
            ->first();

        return $result ? static::newFromDatabase($result) : null;
    }

    /**
     * Trova un cliente per codice fiscale
     */
    public static function findByCodiceFiscale(string $codiceFiscale): ?static
    {
        $result = static::query()
            ->where('codice_fiscale', '=', strtoupper($codiceFiscale))
            ->first();

        return $result ? static::newFromDatabase($result) : null;
    }

    /**
     * Trova un cliente per partita IVA
     */
    public static function findByPartitaIva(string $partitaIva): ?static
    {
        $result = static::query()
            ->where('partita_iva', '=', $partitaIva)
            ->first();

        return $result ? static::newFromDatabase($result) : null;
    }

    /**
     * Cerca clienti per nome, ragione sociale o email
     */
    public static function search(string $termine): array
    {
        $results = static::query()
            ->where('nome', 'LIKE', "%$termine%")
            ->orWhere('cognome', 'LIKE', "%$termine%")
            ->orWhere('ragione_sociale', 'LIKE', "%$termine%")
            ->orWhere('email', 'LIKE', "%$termine%")
            ->orderBy('nome')
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }

    /**
     * Ottiene i clienti più importanti per volume di acquisti
     */
    public static function topClienti(int $limit = 10): array
    {
        $results = static::query()
            ->select(['customers.*', 'SUM(orders.totale) as volume_acquisti'])
            ->leftJoin('orders', 'customers.id = orders.customer_id')
            ->groupBy('customers.id')
            ->orderBy('volume_acquisti', 'DESC')
            ->limit($limit)
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }
}
