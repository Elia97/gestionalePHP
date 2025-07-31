<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? "{$pageTitle} - " : '' ?>MES PHP</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <header class="main-header">
        <nav class="main-nav">
            <a href="/" class="logo">MES PHP</a>
            <ul class="nav-links">
                <li><a href="/" class="<?= $router->getCurrentPath() === '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/users" class="<?= $router->getCurrentPath() === '/users' ? 'active' : '' ?>">Utenti</a></li>
                <li><a href="/customers" class="<?= $router->getCurrentPath() === '/customers' ? 'active' : '' ?>">Clienti</a></li>
                <li><a href="/products" class="<?= $router->getCurrentPath() === '/products' ? 'active' : '' ?>">Prodotti</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <?= $content ?>
    </main>

    <footer class="main-footer">
        <p>&copy; 2025 MES PHP - Sistema di Gestione</p>
    </footer>
</body>

</html>