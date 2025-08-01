<?php

/**
 * Renderizza le carte di navigazione
 * @param array $cards Array delle carte con struttura: icon, title, description, link, button_text
 */
function renderNavigationCards($cards)
{
    if (empty($cards) || !is_array($cards)) {
        return;
    }
?>
    <div class="navigation-cards">
        <?php foreach ($cards as $card): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($card['icon']); ?> <?php echo htmlspecialchars($card['title']); ?></h3>
                <p><?php echo htmlspecialchars($card['description']); ?></p>
                <a href="<?php echo htmlspecialchars($card['link']); ?>" class="btn"><?php echo htmlspecialchars($card['button_text']); ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}

/**
 * Restituisce le carte di navigazione predefinite del sistema
 * @return array Array delle carte di navigazione
 */
function getDefaultNavigationCards()
{
    return [
        [
            'icon' => '👥',
            'title' => 'Gestione Utenti',
            'description' => 'Visualizza e gestisci gli utenti del sistema',
            'link' => '/users',
            'button_text' => 'Vai agli Utenti'
        ],
        [
            'icon' => '👤',
            'title' => 'Gestione Clienti',
            'description' => 'Visualizza e gestisci i clienti',
            'link' => '/customers',
            'button_text' => 'Vai ai Clienti'
        ],
        [
            'icon' => '📦',
            'title' => 'Gestione Prodotti',
            'description' => 'Visualizza e gestisci i prodotti',
            'link' => '/products',
            'button_text' => 'Vai ai Prodotti'
        ],
        [
            'icon' => '🏬',
            'title' => 'Gestione Magazzini',
            'description' => 'Visualizza e gestisci i magazzini',
            'link' => '/warehouses',
            'button_text' => 'Vai ai Magazzini'
        ]
    ];
}

/**
 * Funzione helper per retrocompatibilità
 * Se non vengono passate carte, usa quelle predefinite
 * @param array|null $cards Array delle carte o null per usare quelle predefinite
 */
if (!function_exists('navigation_cards')) {
    function navigation_cards($cards = null)
    {
        if ($cards === null) {
            $cards = getDefaultNavigationCards();
        }
        renderNavigationCards($cards);
    }
}
?>