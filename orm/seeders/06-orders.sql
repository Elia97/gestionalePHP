-- Seeder per la tabella orders
-- Nota: Questo seeder presuppone che esistano già clienti e utenti nel database
-- Inserisci 80 ordini usando CTE (Common Table Expressions)

WITH
    Numbers AS (
        -- Genera numeri da 1 a 80
        SELECT 1 as num
        UNION ALL
        SELECT 2
        UNION ALL
        SELECT 3
        UNION ALL
        SELECT 4
        UNION ALL
        SELECT 5
        UNION ALL
        SELECT 6
        UNION ALL
        SELECT 7
        UNION ALL
        SELECT 8
        UNION ALL
        SELECT 9
        UNION ALL
        SELECT 10
        UNION ALL
        SELECT 11
        UNION ALL
        SELECT 12
        UNION ALL
        SELECT 13
        UNION ALL
        SELECT 14
        UNION ALL
        SELECT 15
        UNION ALL
        SELECT 16
        UNION ALL
        SELECT 17
        UNION ALL
        SELECT 18
        UNION ALL
        SELECT 19
        UNION ALL
        SELECT 20
        UNION ALL
        SELECT 21
        UNION ALL
        SELECT 22
        UNION ALL
        SELECT 23
        UNION ALL
        SELECT 24
        UNION ALL
        SELECT 25
        UNION ALL
        SELECT 26
        UNION ALL
        SELECT 27
        UNION ALL
        SELECT 28
        UNION ALL
        SELECT 29
        UNION ALL
        SELECT 30
        UNION ALL
        SELECT 31
        UNION ALL
        SELECT 32
        UNION ALL
        SELECT 33
        UNION ALL
        SELECT 34
        UNION ALL
        SELECT 35
        UNION ALL
        SELECT 36
        UNION ALL
        SELECT 37
        UNION ALL
        SELECT 38
        UNION ALL
        SELECT 39
        UNION ALL
        SELECT 40
        UNION ALL
        SELECT 41
        UNION ALL
        SELECT 42
        UNION ALL
        SELECT 43
        UNION ALL
        SELECT 44
        UNION ALL
        SELECT 45
        UNION ALL
        SELECT 46
        UNION ALL
        SELECT 47
        UNION ALL
        SELECT 48
        UNION ALL
        SELECT 49
        UNION ALL
        SELECT 50
        UNION ALL
        SELECT 51
        UNION ALL
        SELECT 52
        UNION ALL
        SELECT 53
        UNION ALL
        SELECT 54
        UNION ALL
        SELECT 55
        UNION ALL
        SELECT 56
        UNION ALL
        SELECT 57
        UNION ALL
        SELECT 58
        UNION ALL
        SELECT 59
        UNION ALL
        SELECT 60
        UNION ALL
        SELECT 61
        UNION ALL
        SELECT 62
        UNION ALL
        SELECT 63
        UNION ALL
        SELECT 64
        UNION ALL
        SELECT 65
        UNION ALL
        SELECT 66
        UNION ALL
        SELECT 67
        UNION ALL
        SELECT 68
        UNION ALL
        SELECT 69
        UNION ALL
        SELECT 70
        UNION ALL
        SELECT 71
        UNION ALL
        SELECT 72
        UNION ALL
        SELECT 73
        UNION ALL
        SELECT 74
        UNION ALL
        SELECT 75
        UNION ALL
        SELECT 76
        UNION ALL
        SELECT 77
        UNION ALL
        SELECT 78
        UNION ALL
        SELECT 79
        UNION ALL
        SELECT 80
    ),
    StatusOptions AS (
        -- Stati possibili degli ordini
        SELECT 'pending' as status
        UNION ALL
        SELECT 'processing'
        UNION ALL
        SELECT 'shipped'
        UNION ALL
        SELECT 'delivered'
        UNION ALL
        SELECT 'cancelled'
    )
INSERT INTO
    dbo.orders (
        customer_id,
        user_id,
        status,
        total,
        created_at,
        updated_at
    )
SELECT
    c.id as customer_id,
    u.id as user_id,
    s.status,
    ROUND(
        20.00 + (ABS(CHECKSUM(NEWID())) % 1980),
        2
    ) as total,
    DATEADD(
        DAY,
        - (ABS(CHECKSUM(NEWID())) % 90),
        GETDATE()
    ) as created_at,
    DATEADD(
        DAY,
        - (ABS(CHECKSUM(NEWID())) % 90) + (ABS(CHECKSUM(NEWID())) % 7),
        GETDATE()
    ) as updated_at
FROM Numbers n
    CROSS JOIN (
        SELECT id, ROW_NUMBER() OVER (
                ORDER BY NEWID()
            ) as rn
        FROM customers
    ) c
    CROSS JOIN (
        SELECT id, ROW_NUMBER() OVER (
                ORDER BY NEWID()
            ) as rn
        FROM users
    ) u
    CROSS JOIN (
        SELECT status, ROW_NUMBER() OVER (
                ORDER BY NEWID()
            ) as rn
        FROM StatusOptions
    ) s
WHERE
    c.rn = (
        (n.num - 1) % (
            SELECT COUNT(*)
            FROM customers
        )
    ) + 1
    AND u.rn = (
        (n.num * 2 - 1) % (
            SELECT COUNT(*)
            FROM users
        )
    ) + 1
    AND s.rn = (
        (n.num * 3 - 1) % (
            SELECT COUNT(*)
            FROM StatusOptions
        )
    ) + 1;