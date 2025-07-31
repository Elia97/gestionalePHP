<?php
require_once 'db.php';
?>

<div class="page-content">
    <h1>Benvenuto nel tuo MES PHP!</h1>
    <p>Questo è un sistema di gestione semplice con routing.</p>

    <div class="navigation-cards">
        <div class="card">
            <h3>👥 Gestione Utenti</h3>
            <p>Visualizza e gestisci gli utenti del sistema</p>
            <a href="/users" class="btn">Vai agli Utenti</a>
        </div>

        <div class="card">
            <h3>🏢 Gestione Clienti</h3>
            <p>Visualizza e gestisci i clienti</p>
            <a href="/customers" class="btn">Vai ai Clienti</a>
        </div>
    </div>
</div>