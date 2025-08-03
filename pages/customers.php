<?php
require_once 'db.php';
?>

<div class="page-content">
    <h1>Lista Clienti</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Clienti</span>
    </nav>

    <?php
    $customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)): ?>
        <div class="empty-state">
            <p>Nessun cliente trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="customers-grid">
            <?php foreach ($customers as $customer): ?>
                <div class="customer-card">
                    <h3><?= htmlspecialchars($customer['name']) ?></h3>
                    <div class="customer-info">
                        <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
                        <p><strong>Telefono:</strong> <?= htmlspecialchars($customer['phone'] ?? 'Non specificato') ?></p>
                        <p><strong>Indirizzo:</strong> <?= htmlspecialchars($customer['address'] ?? 'Non specificato') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="page-actions">
        <a href="/" class="btn btn-secondary">← Torna alla Home</a>
    </div>
</div>