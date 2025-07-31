<?php

/**
 * Entry point dell'applicazione
 * Carica configurazione, router e gestisce le route
 */

require_once 'config.php';  // Carica configurazione e variabili .env
require_once 'router.php';
require_once 'includes/helpers.php';

// Crea una nuova istanza del router
$router = new Router();

// Definisci le route
$router->addRoute('/', function () use ($router) {
    renderPage('pages/home.php', 'Home', $router);
});

$router->addRoute('/users', function () use ($router) {
    renderPage('pages/users.php', 'Utenti', $router);
});

$router->addRoute('/customers', function () use ($router) {
    renderPage('pages/customers.php', 'Clienti', $router);
});

// Avvia il routing
$router->route();
