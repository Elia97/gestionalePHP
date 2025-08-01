<?php
require_once 'db.php';
?>

<div class="page-content">
    <h1>Benvenuto nel tuo Gestionale PHP!</h1>
    <p>Questo è un sistema di gestione semplice con routing.</p>

    <?php
    include 'components/navigation-cards.php';
    navigation_cards(); // Usa le carte predefinite
    ?>
</div>