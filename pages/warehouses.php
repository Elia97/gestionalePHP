<?php
require_once 'db.php';
?>

<div class="page-content">
    <h1>Lista Magazzini</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Magazzini</span>
    </nav>

    <?php
    $warehouses = $pdo->query("SELECT * FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($warehouses)): ?>
        <div class="empty-state">
            <p>Nessun magazzino trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="data-grid">
            <?php foreach ($warehouses as $warehouse): ?>
                <div class="data-card">
                    <h3><?= htmlspecialchars($warehouse['name']) ?></h3>
                    <div class="warehouse-info">
                        <p><strong>Indirizzo:</strong> <?= htmlspecialchars($warehouse['address']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="page-actions">
        <a href="/" class="btn btn-secondary">← Torna alla Home</a>
    </div>
</div>