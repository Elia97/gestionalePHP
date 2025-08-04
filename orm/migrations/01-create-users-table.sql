-- Script per la creazione della tabella "users" in SQL Server
CREATE TABLE dbo.users (
    id INT IDENTITY(1, 1) PRIMARY KEY,
    firstName NVARCHAR(100) NOT NULL,
    lastName NVARCHAR(100) NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    email_verified_at DATETIME NULL,
    phone NVARCHAR(20) NULL,
    password NVARCHAR(255) NOT NULL,
    role NVARCHAR(50) NOT NULL DEFAULT 'operator',
    department NVARCHAR(100) NULL,
    remember_token NVARCHAR(100) NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);

-- Commenti per la documentazione delle colonne
EXECUTE sp_addextendedproperty N'MS_Description',
N'Nome dell''utente',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'firstName';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Cognome dell''utente (opzionale)',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'lastName';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Email unica dell''utente',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'email';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data verifica email (nullable)',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'email_verified_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Numero di telefono (opzionale)',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'phone';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Password hashata',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'password';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Ruolo applicativo (default operator)',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'role';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Dipartimento di appartenenza (opzionale)',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'department';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Token per remember me',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'remember_token';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data creazione record',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'created_at';

EXECUTE sp_addextendedproperty N'MS_Description',
N'Data ultima modifica record',
N'schema',
N'dbo',
N'table',
N'users',
N'column',
N'updated_at';