<?php
require_once 'db.php';
?>

<div class="page-content">
    <h1>Lista Utenti</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Utenti</span>
    </nav>

    <?php
    $users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)): ?>
        <div class="empty-state">
            <p>Nessun utente trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="data-grid">
            <?php foreach ($users as $user): ?>
                <div class="data-card">
                    <h3><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h3>
                    <div class="user-info">
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="page-actions">
        <a href="/" class="btn btn-secondary">← Torna alla Home</a>
        <a href="/customers" class="btn">Vai ai Clienti →</a>
    </div>
</div>