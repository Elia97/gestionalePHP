-- Script per la creazione della tabella "orders" in SQL Server
CREATE TABLE dbo.orders (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    customer_id INT NOT NULL,
    user_id INT NOT NULL,
    status NVARCHAR(50) NOT NULL DEFAULT 'pending',
    total DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES dbo.customers (id) ON DELETE CASCADE,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES dbo.users (id) ON DELETE CASCADE
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'ID del cliente che ha effettuato l''ordine',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'customer_id';

EXECUTE sp_addextendedproperty N'MS_Description',
N'ID dell''utente che ha gestito l''ordine',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'user_id';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Stato dell''ordine (default ''pending'')',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'status';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Totale complessivo dell''ordine',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'total';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'orders',
N'column',
N'updated_at';