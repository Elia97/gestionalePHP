<?php
require_once 'orm/DatabaseManager.php';
?>

<div class="page-content">
    <h1>Lista Prodotti</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Prodotti</span>
    </nav>

    <?php
    try {
        $products = DatabaseManager::fetchAll("SELECT * FROM products");
    } catch (Exception $e) {
        $products = [];
    }

    if (empty($products)): ?>
        <div class="empty-state">
            <p>Nessun prodotto trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['code']) ?></h3>
                    <div class="product-info">
                        <p><strong>Nome:</strong> <?= htmlspecialchars($product['name']) ?></p>
                        <p><strong>Descrizione:</strong> <?= htmlspecialchars($product['description'] ?? 'Nessuna descrizione disponibile') ?></p>
                        <p><strong>Prezzo:</strong> €<?= number_format($product['price'], 2, ',', '.') ?></p>
                        <p><strong>Categoria:</strong> <?= htmlspecialchars($product['category'] ?? 'Nessuna categoria disponibile') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="page-actions">
        <a href="/" class="btn btn-secondary">← Torna alla Home</a>
    </div>
</div>