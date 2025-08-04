-- Script per la creazione della tabella "products" in SQL Server
CREATE TABLE dbo.products (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    code NVARCHAR(100) NOT NULL UNIQUE,
    name NVARCHAR(255) NOT NULL,
    description NVARCHAR(MAX) NULL,
    price DECIMAL(10, 2) NOT NULL,
    category NVARCHAR(50) NOT NULL DEFAULT 'informatica',
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT chk_products_category CHECK (
        category IN (
            'informatica',
            'accessori',
            'monitor',
            'storage',
            'networking',
            'audio'
        )
    )
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'Codice unico prodotto',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'code';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Nome del prodotto',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'name';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Descrizione del prodotto (opzionale)',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'description';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Prezzo del prodotto',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'price';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Categoria prodotto (default "informatica")',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'category';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'products',
N'column',
N'updated_at';