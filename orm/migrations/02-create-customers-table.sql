-- Script per la creazione della tabella "customers" in SQL Server
CREATE TABLE dbo.customers (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    phone NVARCHAR(255) NULL,
    address NVARCHAR(255) NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'Nome del cliente',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'name';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Email unica del cliente',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'email';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Telefono del cliente (opzionale)',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'phone';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Indirizzo del cliente (opzionale)',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'address';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'customers',
N'column',
N'updated_at';