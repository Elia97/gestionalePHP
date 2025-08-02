<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? "{$pageTitle} - " : '' ?>Gestionale PHP</title>
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/common.css">

    <?php
    // Carica CSS specifico per pagina se esiste
    if (isset($pageCSS) && !empty($pageCSS)) {
        foreach ((array)$pageCSS as $css) {
            echo "<link rel=\"stylesheet\" href=\"/assets/css/{$css}.css\">\n    ";
        }
    }
    ?>
</head>

<body>
    <header class="main-header">
        <nav class="main-nav">
            <a href="/" class="logo">Gestionale PHP</a>
            <ul class="nav-links">
                <li><a href="/" class="<?= $router->getCurrentPath() === '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/users" class="<?= $router->getCurrentPath() === '/users' ? 'active' : '' ?>">Utenti</a></li>
                <li><a href="/customers" class="<?= $router->getCurrentPath() === '/customers' ? 'active' : '' ?>">Clienti</a></li>
                <li><a href="/products" class="<?= $router->getCurrentPath() === '/products' ? 'active' : '' ?>">Prodotti</a></li>
                <li><a href="/warehouses" class="<?= $router->getCurrentPath() === '/warehouses' ? 'active' : '' ?>">Magazzini</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <?= $content ?>
    </main>

    <footer class="main-footer">
        <p>&copy; 2025 Gestionale PHP - Sistema di Gestione</p>
    </footer>

    <?php
    // Carica JavaScript specifico per pagina se esiste
    if (isset($pageJS) && !empty($pageJS)) {
        foreach ((array)$pageJS as $js) {
            echo "<script src=\"/assets/js/{$js}.js\"></script>\n    ";
        }
    }
    ?>
</body>

</html>