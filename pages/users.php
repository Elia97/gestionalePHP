<?php
require_once 'orm/DatabaseManager.php';
?>

<div class="page-content">
    <h1>Lista Utenti</h1>
    <nav class="breadcrumb">
        <a href="/">Home</a> > <span>Utenti</span>
    </nav>

    <?php
    try {
        $users = DatabaseManager::fetchAll("SELECT * FROM users");
    } catch (Exception $e) {
        $users = [];
    }

    if (empty($users)): ?>
        <div class="empty-state">
            <p>Nessun utente trovato nel sistema.</p>
            <a href="/" class="btn">Torna alla Home</a>
        </div>
    <?php else: ?>
        <div class="users-grid">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
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
    </div>
</div>