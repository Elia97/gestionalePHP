<?php

/**
 * Renderizza una pagina usando il layout comune
 * 
 * @param string $pagePath Percorso del file della pagina
 * @param string $pageTitle Titolo della pagina (passato a layout.php via include)
 * @param Router|null $router Istanza del router (passato a layout.php via include)
 * @param array|string $pageCSS CSS specifici per la pagina (senza estensione .css)
 * @param array|string $pageJS JavaScript specifici per la pagina (senza estensione .js)
 * @return void
 */
function renderPage($pagePath, $pageTitle = '', $router = null, $pageCSS = [], $pageJS = [])
{
    ob_start();
    include $pagePath;
    $content = ob_get_clean();

    include 'includes/layout.php';
}

/**
 * Formatta un importo in euro con localizzazione italiana
 * 
 * @param float|int $amount L'importo da formattare
 * @param int $decimals Numero di decimali (default: 2)
 * @param bool $showSymbol Se mostrare il simbolo dell'euro (default: true)
 * @return string L'importo formattato
 */
function formatCurrency($amount, $decimals = 2, $showSymbol = true)
{
    $formatted = number_format($amount, $decimals, ',', '.');
    return $showSymbol ? "€{$formatted}" : $formatted;
}

/**
 * Formatta un importo in modo compatto (es: 1,2K, 1,5M)
 * 
 * @param float|int $amount L'importo da formattare
 * @param bool $showSymbol Se mostrare il simbolo dell'euro (default: true)
 * @return string L'importo formattato in modo compatto
 */
function formatCurrencyCompact($amount, $showSymbol = true)
{
    $prefix = $showSymbol ? '€' : '';

    if ($amount >= 1000000) {
        return $prefix . ' ' . number_format($amount / 1000000, 1, ',', '.') . 'M';
    } elseif ($amount >= 1000) {
        return $prefix . ' ' . number_format($amount / 1000, 1, ',', '.') . 'K';
    } else {
        return $prefix . ' ' . number_format($amount, 2, ',', '.');
    }
}

/**
 * Formatta una quantità con separatori di migliaia
 * 
 * @param int|float $quantity La quantità da formattare
 * @param string $unit L'unità di misura (default: 'pz')
 * @return string La quantità formattata
 */
function formatQuantity($quantity, $unit = 'pz')
{
    return number_format($quantity, 0, ',', '.') . ' ' . $unit;
}
