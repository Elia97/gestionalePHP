<?php
require_once 'orm/DatabaseManager.php';
require_once 'components/stats_cards.php';
require_once 'components/activity_feed.php';
require_once 'components/navigation_cards.php';
require_once 'components/layout_components.php';
?>

<div class="page-content">
    <?php
    // Header della pagina
    renderPageHeader(
        'Benvenuto nel tuo Gestionale PHP!',
        'Un sistema di gestione moderno e responsive con un design system centralizzato basato su variabili CSS.',
        'center'
    );

    // Sezione di navigazione principale
    startSection('navigation-section');
    renderSectionHeader(
        'Navigazione Principale',
        'Accedi rapidamente alle funzionalità principali del sistema.'
    );
    navigation_cards(); // Usa le carte predefinite
    endSection();

    // Stats cards
    startSection();
    renderSectionHeader('Statistiche del Sistema', 'Panoramica delle metriche principali');
    statsCards(); // Ora usa DatabaseManager interno
    endSection();

    // Sezione attività recenti
    startSection('activity-section');
    renderSectionHeader('Attività Recenti', 'Tieni traccia delle ultime modifiche al sistema.');
    renderViewAllLink('#', 'Vedi tutte');
    activityFeed();
    endSection();
    ?>
</div>