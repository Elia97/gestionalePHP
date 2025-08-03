<?php

/**
 * Renderizza le carte statistiche
 * @param array $stats Array delle statistiche con struttura: title, value, icon, type
 */
function renderStatsCards($stats)
{
    if (empty($stats) || !is_array($stats)) {
        return;
    }
?>
    <div class="stats-grid">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-card <?php echo isset($stat['type']) ? htmlspecialchars($stat['type']) : ''; ?>">
                <div class="d-flex justify-between items-center">
                    <div>
                        <div class="stat-title"><?php echo htmlspecialchars($stat['title']); ?></div>
                        <div class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></div>
                    </div>
                    <div class="stat-icon"><?php echo htmlspecialchars($stat['icon']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}

/**
 * Restituisce le statistiche avanzate dal database
 * @param PDO $pdo Connessione al database
 * @return array Array delle statistiche avanzate
 */
function getAdvancedStatsFromDatabase($pdo)
{
    try {
        $stats = [];

        // Utenti attivi negli ultimi 30 giorni
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $activeUsers = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Utenti Attivi',
            'value' => number_format($activeUsers),
            'icon' => '🟢',
            'type' => 'success'
        ];

        // Prodotti con stock basso (meno di 10 unità)
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock < 10");
        $lowStock = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Stock Basso',
            'value' => number_format($lowStock),
            'icon' => '⚠️',
            'type' => $lowStock > 0 ? 'warning' : 'success'
        ];

        // Ordini di oggi
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()");
        $todayOrders = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Ordini Oggi',
            'value' => number_format($todayOrders),
            'icon' => '📋',
            'type' => 'info'
        ];

        // Ricavi del mese
        $stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) as revenue FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'completed'");
        $monthRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;
        $stats[] = [
            'title' => 'Ricavi Mese',
            'value' => '€' . number_format($monthRevenue, 2),
            'icon' => '💰',
            'type' => 'success'
        ];

        return $stats;
    } catch (PDOException $e) {
        // In caso di errore, usa le statistiche base
        error_log("Errore nel recupero delle statistiche avanzate: " . $e->getMessage());
        return getStatsFromDatabase($pdo);
    }
}

/**
 * Renderizza statistiche personalizzate con query custom
 * @param PDO $pdo Connessione al database
 * @param array $queries Array di configurazione per le query: ['title' => string, 'query' => string, 'icon' => string, 'type' => string]
 */
function customStatsCards($pdo, $queries)
{
    $stats = [];

    foreach ($queries as $config) {
        try {
            $stmt = $pdo->query($config['query']);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $value = $result ? array_values($result)[0] : 0;

            $stats[] = [
                'title' => $config['title'],
                'value' => is_numeric($value) ? number_format($value) : $value,
                'icon' => $config['icon'] ?? '📊',
                'type' => $config['type'] ?? ''
            ];
        } catch (PDOException $e) {
            error_log("Errore nella query personalizzata: " . $e->getMessage());
            $stats[] = [
                'title' => $config['title'],
                'value' => 'Errore',
                'icon' => '❌',
                'type' => 'danger'
            ];
        }
    }

    renderStatsCards($stats);
}

/**
 * Renderizza le statistiche avanzate dal database
 * @param PDO|null $pdo Connessione al database (opzionale)
 */
function advancedStatsCards($pdo = null)
{
    $stats = $pdo ? getAdvancedStatsFromDatabase($pdo) : getDefaultStats();
    renderStatsCards($stats);
}

/**
 * Restituisce le statistiche dal database
 * @param PDO $pdo Connessione al database
 * @return array Array delle statistiche
 */
function getStatsFromDatabase($pdo)
{
    try {
        $stats = [];

        // Conta utenti totali
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Utenti Totali',
            'value' => number_format($userCount),
            'icon' => '👥',
            'type' => ''
        ];

        // Conta prodotti
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Prodotti',
            'value' => number_format($productCount),
            'icon' => '📦',
            'type' => 'success'
        ];

        // Conta magazzini
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM warehouses");
        $warehouseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Magazzini',
            'value' => number_format($warehouseCount),
            'icon' => '🏪',
            'type' => 'warning'
        ];

        // Conta clienti
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
        $customerCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        $stats[] = [
            'title' => 'Clienti',
            'value' => number_format($customerCount),
            'icon' => '🤝',
            'type' => 'info'
        ];

        return $stats;
    } catch (PDOException $e) {
        // In caso di errore, restituisci statistiche di fallback
        error_log("Errore nel recupero delle statistiche: " . $e->getMessage());
        return getDefaultStats();
    }
}

/**
 * Restituisce le statistiche predefinite come fallback
 * @return array Array delle statistiche di default
 */
function getDefaultStats()
{
    return [
        [
            'title' => 'Utenti Totali',
            'value' => '0',
            'icon' => '👥',
            'type' => ''
        ],
        [
            'title' => 'Prodotti',
            'value' => '0',
            'icon' => '📦',
            'type' => 'success'
        ],
        [
            'title' => 'Magazzini',
            'value' => '0',
            'icon' => '🏪',
            'type' => 'warning'
        ],
        [
            'title' => 'Clienti',
            'value' => '0',
            'icon' => '🤝',
            'type' => 'info'
        ]
    ];
}

/**
 * Renderizza le statistiche dal database
 * @param PDO|null $pdo Connessione al database (opzionale)
 */
function statsCards($pdo = null)
{
    $stats = $pdo ? getStatsFromDatabase($pdo) : getDefaultStats();
    renderStatsCards($stats);
}

?>