<?php

/**
 * Esempi di utilizzo dell'ORM con SQL Server
 */

require_once __DIR__ . '/orm/ORM.php';

// Inizializza l'ORM
ORM::init();

echo "🚀 Esempi di utilizzo dell'ORM (SQL Server)\n\n";

// ================================
// ESEMPI CON UTENTI
// ================================

echo "👥 === ESEMPI UTENTI ===\n\n";

// Creare un nuovo utente
echo "📝 Creazione nuovo utente:\n";
$nuovoUtente = new User([
    'firstName' => 'Marco',
    'lastName' => 'Rossi',
    'email' => 'marco.rossi@test.com',
    'role' => User::ROLE_OPERATOR,
    'department' => 'IT'
]);

$nuovoUtente->setPassword('password123');
$nuovoUtente->save();

echo "  ✅ Utente creato con ID: {$nuovoUtente->id}\n";
echo "  👤 Nome completo: {$nuovoUtente->getFullName()}\n";

// Trovare un utente per ID
echo "\n🔍 Ricerca utente per ID:\n";
$utente = User::find(1);
if ($utente) {
    echo "  ✅ Trovato: {$utente->getFullName()} ({$utente->email})\n";
    echo "  🏢 Dipartimento: " . ($utente->department ?? 'Non specificato') . "\n";
    echo "  🎭 Ruolo: {$utente->role}\n";
}

// Trovare un utente per email
echo "\n📧 Ricerca utente per email:\n";
$adminUser = User::findByEmail('admin@gestionale.it');
if ($adminUser) {
    echo "  ✅ Trovato: {$adminUser->getFullName()} - Ruolo: {$adminUser->role}\n";
    echo "  🔐 È admin? " . ($adminUser->isAdmin() ? 'Sì' : 'No') . "\n";
    echo "  ✉️ Email verificata? " . ($adminUser->isActive() ? 'Sì' : 'No') . "\n";
} else {
    echo "  ℹ️ Nessun admin trovato con quella email\n";
}

// Ottenere tutti gli utenti con email verificata
echo "\n✅ Tutti gli utenti verificati:\n";
$utentiVerificati = User::verified()->get();
foreach ($utentiVerificati as $utente) {
    echo "  • {$utente['firstName']} {$utente['lastName']} ({$utente['email']})\n";
}

// Ottenere utenti per ruolo
echo "\n👑 Amministratori:\n";
$admins = User::admins()->get();
if (empty($admins)) {
    echo "  ℹ️ Nessun amministratore trovato\n";
} else {
    foreach ($admins as $admin) {
        echo "  • {$admin['firstName']} {$admin['lastName']}\n";
    }
}

echo "\n👔 Manager:\n";
$managers = User::managers()->get();
if (empty($managers)) {
    echo "  ℹ️ Nessun manager trovato\n";
} else {
    foreach ($managers as $manager) {
        echo "  • {$manager['firstName']} {$manager['lastName']}\n";
    }
}

// Cercare utenti
echo "\n🔎 Ricerca utenti con 'Mar':\n";
$risultati = User::search('Mar');
foreach ($risultati as $utente) {
    echo "  • {$utente->getFullName()} ({$utente->email})\n";
}

// ================================
// ESEMPI DI QUERY AVANZATE
// ================================

echo "\n\n🔍 === QUERY AVANZATE ===\n\n";

// Query personalizzata con QueryBuilder
echo "🛠️ Query personalizzata - Utenti per dipartimento IT:\n";
$utentiIT = User::byDepartment('IT')->get();
foreach ($utentiIT as $utente) {
    echo "  • {$utente['firstName']} {$utente['lastName']} - {$utente['role']}\n";
}

// Ottenere tutti i dipartimenti
echo "\n🏢 Dipartimenti disponibili:\n";
$dipartimenti = User::getDepartments();
if (empty($dipartimenti)) {
    echo "  ℹ️ Nessun dipartimento specificato\n";
} else {
    foreach ($dipartimenti as $dip) {
        echo "  • $dip\n";
    }
}

// Statistiche per ruolo
echo "\n📊 Statistiche per ruolo:\n";
$statsRuoli = User::getRoleStats();
foreach ($statsRuoli as $ruolo => $count) {
    echo "  📈 $ruolo: $count utenti\n";
}

// Contare record
echo "\n📊 Statistiche generali:\n";
echo "  👥 Totale utenti: " . User::query()->count() . "\n";
echo "  ✅ Utenti verificati: " . User::verified()->count() . "\n";
echo "  👑 Amministratori: " . User::admins()->count() . "\n";
echo "  👔 Manager: " . User::managers()->count() . "\n";
echo "  🔧 Operatori: " . User::operators()->count() . "\n";

// ================================
// ESEMPI DI TRANSAZIONI
// ================================

echo "\n\n💼 === TRANSAZIONI ===\n\n";

echo "🔄 Esempio di transazione - Creazione multipla:\n";
try {
    DatabaseManager::transaction(function () {
        // Creiamo più utenti nella stessa transazione
        $utenti = [
            [
                'firstName' => 'Anna',
                'lastName' => 'Verdi',
                'email' => 'anna.verdi@test.com',
                'role' => User::ROLE_MANAGER,
                'department' => 'HR'
            ],
            [
                'firstName' => 'Luigi',
                'lastName' => 'Bianchi',
                'email' => 'luigi.bianchi@test.com',
                'role' => User::ROLE_OPERATOR,
                'department' => 'Produzione'
            ]
        ];

        foreach ($utenti as $userData) {
            $user = new User($userData);
            $user->setPassword('password123');
            $user->save();
            echo "  ✅ Utente creato: {$user->getFullName()}\n";
        }
    });
} catch (Exception $e) {
    echo "  ❌ Errore nella transazione: " . $e->getMessage() . "\n";
}

// ================================
// ESEMPI DI VALIDAZIONE
// ================================

echo "\n\n✅ === VALIDAZIONE ===\n\n";

echo "🔐 Test validazione password:\n";
$testUser = User::find(1);
if ($testUser) {
    $passwordCorretta = $testUser->verifyPassword('password123');
    $passwordSbagliata = $testUser->verifyPassword('password_sbagliata');

    echo "  ✅ Password corretta: " . ($passwordCorretta ? 'Valida' : 'Non valida') . "\n";
    echo "  ❌ Password errata: " . ($passwordSbagliata ? 'Valida' : 'Non valida') . "\n";
}

// ================================
// ESEMPI DI AGGIORNAMENTO
// ================================

echo "\n\n📝 === AGGIORNAMENTO ===\n\n";

echo "🔄 Aggiornamento utente:\n";
$utenteTest = User::findByEmail('marco.rossi@test.com');
if ($utenteTest) {
    $utenteTest->department = 'Sviluppo';
    $utenteTest->save();
    echo "  ✅ Dipartimento aggiornato: {$utenteTest->getFullName()} -> {$utenteTest->department}\n";
}

// ================================
// TEST VERIFICA EMAIL
// ================================

echo "\n\n📧 === VERIFICA EMAIL ===\n\n";

echo "✉️ Test verifica email:\n";
if ($utenteTest) {
    if (!$utenteTest->isActive()) {
        $utenteTest->markEmailAsVerified();
        echo "  ✅ Email verificata per: {$utenteTest->getFullName()}\n";
    } else {
        echo "  ℹ️ Email già verificata per: {$utenteTest->getFullName()}\n";
    }
}

// ================================
// ESEMPI CON ALTRE TABELLE
// ================================

echo "\n\n📋 === INFORMAZIONI DATABASE ===\n\n";

echo "🗄️ Tabelle nel database:\n";
try {
    $tables = DatabaseManager::fetchAll("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_TYPE = 'BASE TABLE' 
        AND TABLE_CATALOG = DB_NAME()
        ORDER BY TABLE_NAME
    ");

    foreach ($tables as $table) {
        echo "  📄 {$table['TABLE_NAME']}\n";
    }
} catch (Exception $e) {
    echo "  ❌ Errore nel recupero tabelle: " . $e->getMessage() . "\n";
}

echo "\n🎉 Esempi completati!\n";

echo "\n💡 Prossimi passi:\n";
echo "  1. Crea modelli per le altre tabelle (customers, products, ecc.)\n";
echo "  2. Esegui i seeder per popolare il database\n";
echo "  3. Testa l'integrazione con le pagine esistenti\n";
