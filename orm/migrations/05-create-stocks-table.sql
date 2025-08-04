-- Script per la creazione della tabella "stocks" in SQL Server
CREATE TABLE dbo.stocks (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    product_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT uq_stocks_product_warehouse UNIQUE (product_id, warehouse_id),
    CONSTRAINT fk_stocks_product FOREIGN KEY (product_id) REFERENCES dbo.products (id) ON DELETE CASCADE,
    CONSTRAINT fk_stocks_warehouse FOREIGN KEY (warehouse_id) REFERENCES dbo.warehouses (id) ON DELETE CASCADE
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'ID del prodotto collegato',
N'schema',
N'dbo',
N'table',
N'stocks',
N'column',
N'product_id';

EXECUTE sp_addextendedproperty N'MS_Description',
N'ID del magazzino collegato',
N'schema',
N'dbo',
N'table',
N'stocks',
N'column',
N'warehouse_id';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Quantità disponibile del prodotto nel magazzino',
N'schema',
N'dbo',
N'table',
N'stocks',
N'column',
N'quantity';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'stocks',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'stocks',
N'column',
N'updated_at';