-- Script per la creazione della tabella "warehouses" in SQL Server
CREATE TABLE dbo.warehouses (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL UNIQUE,
    address NVARCHAR(255) NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'Nome univoco del magazzino',
N'schema',
N'dbo',
N'table',
N'warehouses',
N'column',
N'name';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Indirizzo del magazzino (opzionale)',
N'schema',
N'dbo',
N'table',
N'warehouses',
N'column',
N'address';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'warehouses',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'warehouses',
N'column',
N'updated_at';