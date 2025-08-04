-- Seeder per la tabella stocks
-- Nota: Questo seeder presuppone che esistano già prodotti e magazzini nel database
-- Genera stock per ogni combinazione prodotto-magazzino utilizzando CTE

WITH
    ProductWarehouseCombinations AS (
        -- Genera combinazioni casuali di prodotti e magazzini
        SELECT
            p.id as product_id,
            w.id as warehouse_id,
            ROW_NUMBER() OVER (
                ORDER BY NEWID()
            ) as combination_num
        FROM (
                SELECT id, ROW_NUMBER() OVER (
                        ORDER BY NEWID()
                    ) as rn
                FROM dbo.products
            ) p
            CROSS JOIN (
                SELECT id, ROW_NUMBER() OVER (
                        ORDER BY NEWID()
                    ) as rn
                FROM dbo.warehouses
            ) w
        WHERE
            -- Non tutti i prodotti sono in tutti i magazzini (70% di probabilità)
            (
                ABS(
                    CHECKSUM(NEWID()) + p.id + w.id
                ) % 100
            ) < 70
    )
INSERT INTO
    dbo.stocks (
        product_id,
        warehouse_id,
        quantity,
        created_at,
        updated_at
    )
SELECT
    product_id,
    warehouse_id,
    -- Quantità casuale tra 10 e 500
    (
        ABS(
            CHECKSUM(NEWID()) + combination_num
        ) % 490
    ) + 10 as quantity,
    -- Date casuali negli ultimi 30 giorni
    DATEADD(
        DAY,
        - (
            ABS(
                CHECKSUM(NEWID()) + combination_num * 2
            ) % 30
        ),
        GETDATE()
    ) as created_at,
    DATEADD(
        DAY,
        - (
            ABS(
                CHECKSUM(NEWID()) + combination_num * 3
            ) % 30
        ),
        GETDATE()
    ) as updated_at
FROM ProductWarehouseCombinations;