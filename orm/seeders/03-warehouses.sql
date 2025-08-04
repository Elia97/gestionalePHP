-- Seeder per la tabella warehouses

INSERT INTO
    dbo.warehouses (
        name,
        address,
        created_at,
        updated_at
    )
VALUES (
        'Magazzino Centrale',
        'Via Roma 123, Milano, 20100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Magazzino Nord',
        'Via Torino 45, Torino, 10100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Magazzino Sud',
        'Via Napoli 67, Napoli, 80100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Deposito Est',
        'Via Venezia 89, Venezia, 30100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Deposito Ovest',
        'Via Genova 12, Genova, 16100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Magazzino Logistico',
        'Via Bologna 34, Bologna, 40100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Centro Distribuzione',
        'Via Firenze 56, Firenze, 50100',
        GETDATE(),
        GETDATE()
    ),
    (
        'Deposito Temporaneo',
        'Via Palermo 78, Palermo, 90100',
        GETDATE(),
        GETDATE()
    );