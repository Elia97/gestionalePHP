-- ================================================
-- -- Migrazione: Create posts table
-- Creata: 2025-08-04 11:27:26
-- ================================================

-- Crea la tabella posts
CREATE TABLE [posts] (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(255) NOT NULL,
    slug NVARCHAR(255) UNIQUE NOT NULL,
    content NVARCHAR(MAX),
    author_id INT,
    status NVARCHAR(20) DEFAULT 'draft',
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),

-- Foreign key verso users
CONSTRAINT FK_posts_author_id FOREIGN KEY (author_id) REFERENCES [users](id)
);

-- Indici per performance
CREATE INDEX IX_posts_slug ON [posts] (slug);

CREATE INDEX IX_posts_status ON [posts] (status);

CREATE INDEX IX_posts_author_id ON [posts] (author_id);

CREATE INDEX IX_posts_created_at ON [posts] (created_at);

-- Commenti tabella (opzionale)
-- EXEC sp_addextendedproperty
--     @name = N'MS_Description',
--     @value = N'Descrizione della tabella posts',
--     @level0type = N'SCHEMA', @level0name = N'dbo',
--     @level1type = N'TABLE', @level1name = N'posts';