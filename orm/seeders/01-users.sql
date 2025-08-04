-- Seeder per la tabella users

INSERT INTO
    dbo.users (
        firstName,
        lastName,
        email,
        email_verified_at,
        phone,
        password,
        role,
        department,
        created_at,
        updated_at
    )
VALUES (
        'Mario',
        'Rossi',
        'mario.rossi@azienda.com',
        GETDATE(),
        '+39 333 1234567',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'IT',
        GETDATE(),
        GETDATE()
    ),
    (
        'Giulia',
        'Bianchi',
        'giulia.bianchi@azienda.com',
        GETDATE(),
        '+39 334 2345678',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'IT',
        GETDATE(),
        GETDATE()
    ),
    (
        'Luca',
        'Verdi',
        'luca.verdi@azienda.com',
        GETDATE(),
        '+39 335 3456789',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'produzione',
        GETDATE(),
        GETDATE()
    ),
    (
        'Anna',
        'Neri',
        'anna.neri@azienda.com',
        GETDATE(),
        '+39 336 4567890',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'produzione',
        GETDATE(),
        GETDATE()
    ),
    (
        'Francesco',
        'Ferrari',
        'francesco.ferrari@azienda.com',
        GETDATE(),
        '+39 337 5678901',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'produzione',
        GETDATE(),
        GETDATE()
    ),
    (
        'Sofia',
        'Romano',
        'sofia.romano@azienda.com',
        GETDATE(),
        '+39 338 6789012',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'produzione',
        GETDATE(),
        GETDATE()
    ),
    (
        'Alessandro',
        'Galli',
        'alessandro.galli@azienda.com',
        GETDATE(),
        '+39 339 7890123',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'logistica',
        GETDATE(),
        GETDATE()
    ),
    (
        'Martina',
        'Conti',
        'martina.conti@azienda.com',
        GETDATE(),
        '+39 340 8901234',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'logistica',
        GETDATE(),
        GETDATE()
    ),
    (
        'Davide',
        'Ricci',
        'davide.ricci@azienda.com',
        GETDATE(),
        '+39 341 9012345',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'logistica',
        GETDATE(),
        GETDATE()
    ),
    (
        'Elisa',
        'Marino',
        'elisa.marino@azienda.com',
        GETDATE(),
        '+39 342 0123456',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'IT',
        GETDATE(),
        GETDATE()
    ),
    (
        'Matteo',
        'Greco',
        'matteo.greco@azienda.com',
        GETDATE(),
        '+39 343 1234567',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'operator',
        'IT',
        GETDATE(),
        GETDATE()
    );