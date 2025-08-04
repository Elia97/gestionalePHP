-- Seeder per la tabella order_items
-- Nota: Questo seeder presuppone che esistano già ordini e prodotti nel database
-- Genera items per ogni ordine esistente utilizzando CTE

WITH
    OrderedOrders AS (
        -- Seleziona tutti gli ordini esistenti con un numero di riga
        SELECT id, ROW_NUMBER() OVER (
                ORDER BY id
            ) as order_num
        FROM dbo.orders
    ),
    OrderedProducts AS (
        -- Seleziona tutti i prodotti esistenti con un numero di riga
        SELECT id, price, ROW_NUMBER() OVER (
                ORDER BY id
            ) as product_num
        FROM dbo.products
    ),
    ItemsToGenerate AS (
        -- Genera items per ogni ordine con distribuzione realistica
        SELECT
            o.id as order_id,
            p.id as product_id,
            p.price as original_price,
            (
                ABS(
                    CHECKSUM(NEWID()) + o.id + p.id
                ) % 5
            ) + 1 as quantity,
            ROW_NUMBER() OVER (
                PARTITION BY
                    o.id
                ORDER BY NEWID()
            ) as item_order,
            -- Determina il numero massimo di items per questo ordine (1-4 items)
            CASE
                WHEN (
                    ABS(CHECKSUM(NEWID()) + o.id) % 100
                ) < 25 THEN 1 -- 25% ordini con 1 item
                WHEN (
                    ABS(CHECKSUM(NEWID()) + o.id) % 100
                ) < 60 THEN 2 -- 35% ordini con 2 items  
                WHEN (
                    ABS(CHECKSUM(NEWID()) + o.id) % 100
                ) < 85 THEN 3 -- 25% ordini con 3 items
                ELSE 4 -- 15% ordini con 4 items
            END as max_items_for_order
        FROM
            OrderedOrders o
            CROSS JOIN OrderedProducts p
        WHERE
            -- Solo alcuni prodotti per ordine
            (
                ABS(
                    CHECKSUM(NEWID()) + o.id * 11 + p.id * 7
                ) % 100
            ) < 50
    )
INSERT INTO
    dbo.order_items (
        order_id,
        product_id,
        quantity,
        price,
        created_at,
        updated_at
    )
SELECT
    order_id,
    product_id,
    quantity,
    ROUND(
        original_price * (
            0.8 + (
                ABS(
                    CHECKSUM(NEWID()) + order_id + product_id
                ) % 40
            ) / 100.0
        ),
        2
    ) as price, -- Prezzo con variazione ±20%
    DATEADD(
        MINUTE,
        - (
            ABS(
                CHECKSUM(NEWID()) + order_id * 2
            ) % 1440
        ),
        GETDATE()
    ) as created_at, -- Ora casuale nelle ultime 24h
    DATEADD(
        MINUTE,
        - (
            ABS(
                CHECKSUM(NEWID()) + order_id * 3
            ) % 1440
        ),
        GETDATE()
    ) as updated_at
FROM ItemsToGenerate
WHERE
    item_order <= max_items_for_order;
-- Usa il numero massimo determinato per ogni ordine