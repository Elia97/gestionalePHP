<?php

/**
 * Renderizza il feed delle attività
 * @param array $activities Array delle attività con struttura: title, description, time, icon, type
 */
function renderActivityFeed($activities)
{
    if (empty($activities) || !is_array($activities)) {
        return;
    }
?>
    <div class="activity-feed">
        <?php foreach ($activities as $activity): ?>
            <div class="activity-item">
                <div class="activity-icon <?php echo isset($activity['type']) ? htmlspecialchars($activity['type']) : ''; ?>">
                    <?php echo htmlspecialchars($activity['icon']); ?>
                </div>
                <div class="activity-content">
                    <div class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></div>
                    <div class="activity-description"><?php echo htmlspecialchars($activity['description']); ?></div>
                </div>
                <div class="activity-time"><?php echo htmlspecialchars($activity['time']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}

/**
 * Restituisce le attività predefinite del sistema
 * @return array Array delle attività
 */
function getDefaultActivities()
{
    return [
        [
            'title' => 'Nuovo prodotto aggiunto',
            'description' => 'Laptop Dell XPS 13 aggiunto al catalogo',
            'time' => '2 ore fa',
            'icon' => '✓',
            'type' => 'success'
        ],
        [
            'title' => 'Nuovo utente registrato',
            'description' => 'Marco Rossi si è registrato al sistema',
            'time' => '4 ore fa',
            'icon' => '👤',
            'type' => 'info'
        ],
        [
            'title' => 'Stock in esaurimento',
            'description' => 'iPhone 14 ha solo 3 unità rimaste',
            'time' => '6 ore fa',
            'icon' => '⚠',
            'type' => 'warning'
        ],
        [
            'title' => 'Backup completato',
            'description' => 'Backup automatico del database eseguito con successo',
            'time' => '8 ore fa',
            'icon' => '💾',
            'type' => 'success'
        ],
        [
            'title' => 'Ordine annullato',
            'description' => 'Ordine #1234 annullato dal cliente',
            'time' => '1 giorno fa',
            'icon' => '❌',
            'type' => 'danger'
        ]
    ];
}

/**
 * Renderizza il feed delle attività predefinite
 */
function activityFeed()
{
    $activities = getDefaultActivities();
    renderActivityFeed($activities);
}

?>