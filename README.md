# MES PHP - Sistema di Gestione

Un sistema di gestione aziendale (MES) costruito in PHP puro con routing personalizzato e gestione variabili d'ambiente.

## Caratteristiche

- **Router personalizzato** con URL puliti
- **Sistema .env** per variabili d'ambiente (stile Vite)
- **Layout responsive** con design moderno
- **Supporto multi-database** (MySQL e SQL Server)
- **Zero dipendenze** - completamente autonomo
- **Architettura modulare** con separazione delle responsabilità

## Struttura del Progetto

```text
php/
├── includes/
│   ├── layout.php         # Layout comune (header/footer)
│   └── helpers.php        # Funzioni helper
├── pages/
│    ├── home.php           # Pagina home
│    ├── users.php          # Gestione utenti
│    ├── customers.php      # Gestione clienti
│    └── 404.php            # Pagina errore
├── .env                   # Variabili d'ambiente (da configurare)
├── .env.example           # Template di esempio
├── .htaccess              # Configurazione Apache per routing
├── config.php             # Configurazione applicazione
├── db.php                 # Connessione database
├── env_loader.php         # Parser per file .env
├── index.php              # Entry point dell'applicazione
├── router.php             # Router personalizzato
└── style.css              # Stili CSS

```

## Configurazione

### 1. **Configurazione Database**

Modifica il file `.env` con i tuoi parametri:

```env
# Per MySQL
DB_DRIVER=mysql
DB_HOST=localhost
DB_NAME=mes_database
DB_USER=root
DB_PASS=your_password

# Per SQL Server
DB_DRIVER=sqlsrv
DB_HOST=localhost,1433
DB_NAME=gestionale-aziendale
DB_USER=sa
DB_PASS=your_password
```

### 2. **Configurazione Applicazione**

```env
APP_NAME="Il Tuo MES"
APP_URL=http://localhost:8000
APP_DEBUG=true
APP_ENV=development
```

### 3. **Configurazione Mail (Opzionale)**

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_app_password
```

## Installazione

### 1. **Clone del Progetto**

```bash
git clone <repository-url>
cd php
```

### 2. **Configurazione Environment**

```bash
# Copia il template
copy .env.example .env

# Modifica .env con i tuoi parametri
notepad .env
```

### 3. **Avvio del Server**

```bash
# Avvia server PHP integrato
php -S localhost:8000 index.php

# Oppure usa XAMPP/WAMP
# Posiziona i file nella directory htdocs/www
```

### 4. **Configurazione Apache (Opzionale)**

Il file `.htaccess` è già configurato per il routing. Assicurati che:

- `mod_rewrite` sia abilitato
- `AllowOverride All` sia impostato nella configurazione Apache

## Routing

Il sistema usa un router personalizzato che gestisce URL puliti:

```php
// Definizione route in index.php
$router->addRoute('/', function () use ($router) {
    renderPage('pages/home.php', 'Home', $router);
});

$router->addRoute('/users', function () use ($router) {
    renderPage('pages/users.php', 'Utenti', $router);
});
```

### **URL Disponibili:**

- `/` → Home page
- `/users` → Lista utenti
- `/customers` → Lista clienti
- Qualsiasi altro URL → Pagina 404

## Layout e Template

### **Layout Comune**

Tutte le pagine usano il layout in `includes/layout.php` che include:

- Header con navigazione
- Contenuto dinamico
- Footer

### **Creazione Nuove Pagine**

1. Crea il file in `pages/nome_pagina.php`
2. Aggiungi la route in `index.php`
3. Il layout viene applicato automaticamente

```php
// Esempio nuova pagina
$router->addRoute('/products', function () use ($router) {
    renderPage('pages/products.php', 'Prodotti', $router);
});
```

## Sviluppo

### **Variabili d'Ambiente**

Le variabili sono accessibili ovunque tramite costanti:

```php
echo DB_HOST;      // localhost
echo APP_NAME;     // Il Tuo MES
echo APP_DEBUG;    // true/false
```

### **Database**

Connessione automatica in `db.php`, variabile `$pdo` disponibile globalmente:

```php
$users = $pdo->query("SELECT * FROM users")->fetchAll();
```

### **Debug Mode**

Quando `APP_DEBUG=true`:

- Errori PHP visibili
- Informazioni di configurazione nel terminale
- Messaggi di errore dettagliati

## Features Implementate

### Router Personalizzato

- URL puliti senza estensioni
- Gestione 404 automatica
- Supporto parametri (estendibile)

### Sistema .env

- Parser personalizzato stile Vite/Laravel
- Supporto commenti e quotes
- Espansione variabili `${VAR}`
- Protezione da caricamenti multipli

### Layout System

- Template riutilizzabile
- Navigazione attiva automatica
- Responsive design
- Output buffering per performance

### Multi-Database

- Supporto MySQL e SQL Server
- Configurazione tramite .env
- Connessione automatica con error handling

## Estensioni Future

### **Facilmente Estendibile:**

- Sistema autenticazione
- API REST endpoints
- Middleware personalizzati
- Sistema di validazione
- Logger avanzato
- Cache system

## Sicurezza

- Variabili sensibili in .env (non in repository)
- Prepared statements per query database
- Escape HTML output con `htmlspecialchars()`
- Protezione directory con .htaccess
- Error handling appropriato

## Tecnologie

- **PHP 7.4+** - Linguaggio backend
- **PDO** - Database abstraction layer
- **CSS3** - Styling moderno
- **Apache/Nginx** - Web server (opzionale)

## Contributi

Progetto sviluppato come sistema MES personalizzato con architettura modulare e scalabile.

---

**Obiettivo:** Sistema autonomo, leggero e facilmente estendibile per gestione aziendale.
