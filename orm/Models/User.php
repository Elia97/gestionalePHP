<?php

/**
 * Model User - Gestisce gli utenti del sistema (SQL Server)
 */

require_once __DIR__ . '/../Model.php';

class User extends Model
{
    protected static string $tableName = 'users';
    protected static string $primaryKey = 'id';

    protected static array $fillable = [
        'firstName',
        'lastName',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'role',
        'department',
        'remember_token'
    ];

    protected static array $hidden = [
        'password',
        'remember_token'
    ];

    protected static array $casts = [
        'id' => 'int',
        'email_verified_at' => 'datetime'
    ];

    // Costanti per i ruoli
    public const string ROLE_ADMIN = 'admin';
    public const string ROLE_MANAGER = 'manager';
    public const string ROLE_OPERATOR = 'operator';

    /**
     * Ottiene il nome completo dell'utente
     */
    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    /**
     * Controlla se l'utente è attivo (email verificata)
     */
    public function isActive(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Controlla se l'utente ha un determinato ruolo
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Controlla se l'utente è amministratore
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Controlla se l'utente è manager
     */
    public function isManager(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    /**
     * Imposta la password hashata
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifica la password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Marca l'email come verificata
     */
    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = new DateTime();
        $this->save();
    }

    /**
     * Scope per ottenere solo utenti con email verificata
     */
    public static function verified(): QueryBuilder
    {
        return static::query()->where('email_verified_at', 'IS NOT', 'NULL');
    }

    /**
     * Scope per ottenere utenti per ruolo
     */
    public static function byRole(string $role): QueryBuilder
    {
        return static::query()->where('role', '=', $role);
    }

    /**
     * Scope per ottenere solo amministratori
     */
    public static function admins(): QueryBuilder
    {
        return static::byRole(self::ROLE_ADMIN);
    }

    /**
     * Scope per ottenere solo manager
     */
    public static function managers(): QueryBuilder
    {
        return static::byRole(self::ROLE_MANAGER);
    }

    /**
     * Scope per ottenere solo operatori
     */
    public static function operators(): QueryBuilder
    {
        return static::byRole(self::ROLE_OPERATOR);
    }

    /**
     * Scope per ottenere utenti per dipartimento
     */
    public static function byDepartment(string $department): QueryBuilder
    {
        return static::query()->where('department', '=', $department);
    }

    /**
     * Trova un utente per email
     */
    public static function findByEmail(string $email): ?static
    {
        $result = static::query()
            ->where('email', '=', $email)
            ->first();

        return $result ? static::newFromDatabase($result) : null;
    }

    /**
     * Cerca utenti per nome, cognome o email
     */
    public static function search(string $term): array
    {
        $results = static::query()
            ->where('firstName', 'LIKE', "%$term%")
            ->orWhere('lastName', 'LIKE', "%$term%")
            ->orWhere('email', 'LIKE', "%$term%")
            ->orderBy('firstName')
            ->get();

        return array_map([static::class, 'newFromDatabase'], $results);
    }

    /**
     * Ottiene tutti i dipartimenti
     */
    public static function getDepartments(): array
    {
        $results = static::query()
            ->select(['department'])
            ->where('department', 'IS NOT', 'NULL')
            ->groupBy('department')
            ->orderBy('department')
            ->get();

        return array_column($results, 'department');
    }

    /**
     * Ottiene statistiche per ruolo
     */
    public static function getRoleStats(): array
    {
        $results = static::query()
            ->select(['role', 'COUNT(*) as count'])
            ->groupBy('role')
            ->orderBy('role')
            ->get();

        $stats = [];
        foreach ($results as $result) {
            $stats[$result['role']] = (int) $result['count'];
        }

        return $stats;
    }
}
