<?php
require_once 'orm/DatabaseManager.php';
?>

<div class="page-content">
    <h1>Lista Magazzini</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Magazzini</span>
    </nav>

    <?php
    // Query per ottenere magazzini con le relative giacenze
    $warehousesQuery = "
        SELECT 
            w.id as warehouse_id,
            w.name as warehouse_name,
            w.address as warehouse_address,
            COUNT(s.id) as total_stocks,
            COALESCE(SUM(s.quantity), 0) as total_quantity,
            COALESCE(SUM(s.quantity * p.price), 0) as total_value
        FROM warehouses w
        LEFT JOIN stocks s ON w.id = s.warehouse_id
        LEFT JOIN products p ON s.product_id = p.id
        GROUP BY w.id, w.name, w.address
        ORDER BY w.name
    ";

    try {
        $warehouses = DatabaseManager::fetchAll($warehousesQuery);
    } catch (Exception $e) {
        $warehouses = [];
    }

    if (empty($warehouses)): ?>
        <div class="empty-state">
            <p>Nessun magazzino trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="warehouses-grid">
            <?php foreach ($warehouses as $warehouse): ?>
                <div class="warehouse-card">
                    <div class="warehouse-header">
                        <h3 class="mb-2"><?= htmlspecialchars($warehouse['warehouse_name']) ?></h3>
                        <div class="warehouse-stats">
                            <span class="stat-badge">
                                <?= $warehouse['total_stocks'] ?> prodotti
                            </span>
                            <span class="stat-badge">
                                <?= formatQuantity($warehouse['total_quantity']) ?>
                            </span>
                            <span class="stat-badge success" title="Valore totale: <?= formatCurrency($warehouse['total_value']) ?>">
                                <?= formatCurrencyCompact($warehouse['total_value']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="warehouse-info">
                        <p><?= htmlspecialchars($warehouse['warehouse_address']) ?></p>
                    </div>

                    <?php if ($warehouse['total_stocks'] > 0): ?>
                        <div class="warehouse-actions">
                            <button class="btn btn-sm btn-outline" onclick="toggleStocks(<?= $warehouse['warehouse_id'] ?>)">
                                <span id="toggle-text-<?= $warehouse['warehouse_id'] ?>">Mostra Giacenze</span>
                                <span id="toggle-icon-<?= $warehouse['warehouse_id'] ?>">▼</span>
                            </button>
                        </div>

                        <div id="stocks-<?= $warehouse['warehouse_id'] ?>" class="stocks-details" style="display: none;">
                            <h4 class="text-center">Giacenze dettagliate</h4>
                            <?php
                            // Query per ottenere le giacenze dettagliate di questo magazzino
                            $stocksQuery = "
                                SELECT 
                                    p.name as product_name,
                                    p.code as product_code,
                                    p.price as product_price,
                                    s.quantity,
                                    (s.quantity * p.price) as total_value,
                                    s.updated_at
                                FROM stocks s
                                JOIN products p ON s.product_id = p.id
                                WHERE s.warehouse_id = :warehouse_id
                                ORDER BY p.name
                            ";

                            try {
                                $stocks = DatabaseManager::fetchAll($stocksQuery, [':warehouse_id' => $warehouse['warehouse_id']]);
                            } catch (Exception $e) {
                                $stocks = [];
                            }
                            ?>

                            <?php if (!empty($stocks)): ?>
                                <div class="stocks-list">
                                    <?php foreach ($stocks as $stock): ?>
                                        <div class="stock-item">
                                            <div class="stock-main">
                                                <div class="stock-product">
                                                    <strong><?= htmlspecialchars($stock['product_name']) ?></strong>
                                                    <code><?= htmlspecialchars($stock['product_code']) ?></code>
                                                </div>
                                                <div class="stock-value">
                                                    <span class="quantity"><?= formatQuantity($stock['quantity']) ?></span>
                                                    <span class="total-value"><?= formatCurrency($stock['total_value']) ?></span>
                                                </div>
                                            </div>
                                            <div class="stock-details">
                                                <span class="price">Prezzo: <?= formatCurrency($stock['product_price']) ?></span>
                                                <span class="date">Aggiornato: <?= $stock['updated_at'] ? date('d/m/Y H:i', strtotime($stock['updated_at'])) : 'N/A' ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-stocks">Nessuna giacenza presente in questo magazzino.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-stocks">Nessuna giacenza presente in questo magazzino.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="page-actions">
        <a href="/" class="btn btn-secondary">← Torna alla Home</a>
    </div>
</div>