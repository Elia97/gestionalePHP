<?php

/**
 * Renderizza una pagina usando il layout comune
 * 
 * @param string $pagePath Percorso del file della pagina
 * @param string $pageTitle Titolo della pagina (passato a layout.php via include)
 * @param Router|null $router Istanza del router (passato a layout.php via include)
 * @return void
 */
function renderPage($pagePath, $pageTitle = '', $router = null)
{
    ob_start();
    include $pagePath;
    $content = ob_get_clean();

    include 'includes/layout.php';
}
