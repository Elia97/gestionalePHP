<?php

/**
 * Renderizza un header di sezione
 * @param string $title Titolo della sezione
 * @param string $description Descrizione della sezione (opzionale)
 * @param string $alignment Allineamento del testo (left, center, right)
 */
function renderSectionHeader($title, $description = '', $alignment = 'left')
{
    $alignmentClass = $alignment === 'center' ? ' text-center' : '';
?>
    <div class="section-header<?php echo $alignmentClass; ?>">
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <?php if (!empty($description)): ?>
            <p><?php echo htmlspecialchars($description); ?></p>
        <?php endif; ?>
    </div>
<?php
}

/**
 * Renderizza un header di sezione con h1
 * @param string $title Titolo della sezione
 * @param string $description Descrizione della sezione (opzionale)
 * @param string $alignment Allineamento del testo (left, center, right)
 */
function renderPageHeader($title, $description = '', $alignment = 'left')
{
    $alignmentClass = $alignment === 'center' ? ' text-center' : '';
?>
    <div class="section-header<?php echo $alignmentClass; ?>">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <?php if (!empty($description)): ?>
            <p><?php echo htmlspecialchars($description); ?></p>
        <?php endif; ?>
    </div>
<?php
}

/**
 * Renderizza un header di sezione con h3
 * @param string $title Titolo della sezione
 * @param string $description Descrizione della sezione (opzionale)
 * @param string $alignment Allineamento del testo (left, center, right)
 */
function renderSubsectionHeader($title, $description = '', $alignment = 'left')
{
    $alignmentClass = $alignment === 'center' ? ' text-center' : '';
?>
    <div class="section-header<?php echo $alignmentClass; ?>">
        <h3><?php echo htmlspecialchars($title); ?></h3>
        <?php if (!empty($description)): ?>
            <p><?php echo htmlspecialchars($description); ?></p>
        <?php endif; ?>
    </div>
<?php
}

/**
 * Renderizza un alert/notifica
 * @param string $message Messaggio dell'alert
 * @param string $type Tipo di alert (primary, success, warning, danger, info)
 * @param string $title Titolo dell'alert (opzionale)
 */
function renderAlert($message, $type = 'info', $title = '')
{
?>
    <div class="alert <?php echo htmlspecialchars($type); ?>">
        <?php if (!empty($title)): ?>
            <strong><?php echo htmlspecialchars($title); ?></strong>
        <?php endif; ?>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php
}

/**
 * Renderizza un badge/etichetta
 * @param string $text Testo del badge
 * @param string $type Tipo di badge (primary, success, warning, danger, info)
 */
function renderBadge($text, $type = 'primary')
{
?>
    <span class="stat-badge <?php echo htmlspecialchars($type); ?>">
        <?php echo htmlspecialchars($text); ?>
    </span>
<?php
}

/**
 * Renderizza l'inizio di una sezione con wrapper
 * @param string $class Classe CSS aggiuntiva per la sezione
 */
function startSection($class = '')
{
    $sectionClass = !empty($class) ? ' ' . htmlspecialchars($class) : '';
    echo '<section class="content-section' . $sectionClass . '">';
}

/**
 * Renderizza la fine di una sezione
 */
function endSection()
{
    echo '</section>';
}

/**
 * Renderizza un link "Vedi tutto"
 * @param string $href URL di destinazione
 * @param string $text Testo del link (default: "Vedi tutte")
 */
function renderViewAllLink($href = '#', $text = 'Vedi tutte')
{
?>
    <a href="<?php echo htmlspecialchars($href); ?>" class="view-all-link">
        <?php echo htmlspecialchars($text); ?>
    </a>
<?php
}

?>