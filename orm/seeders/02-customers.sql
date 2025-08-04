-- Seeder per la tabella customers

INSERT INTO
    dbo.customers (
        name,
        email,
        phone,
        address,
        created_at,
        updated_at
    )
VALUES (
        'TechSolutions S.r.l.',
        'info@techsolutions.it',
        '+39 02 1234567',
        'Via Milano 15, 20121 Milano (MI)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Industrie Meccaniche Bianchi S.p.A.',
        'contatti@bianchi-meccanica.it',
        '+39 011 2345678',
        'Corso Torino 88, 10129 Torino (TO)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Logistica Express S.r.l.',
        'ordini@logistica-express.com',
        '+39 051 3456789',
        'Via Emilia 234, 40121 Bologna (BO)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Alimentari Gourmet S.p.A.',
        'vendite@alimentari-gourmet.it',
        '+39 06 4567890',
        'Via Roma 67, 00184 Roma (RM)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Costruzioni Moderni S.r.l.',
        'progetti@costruzioni-moderni.it',
        '+39 055 5678901',
        'Piazza del Duomo 12, 50122 Firenze (FI)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Elettronica Avanzata',
        'info@elettronica-avanzata.com',
        '+39 081 6789012',
        'Via Napoli 45, 80132 Napoli (NA)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Tessile Moderna S.r.l.',
        'commerciale@tessile-moderna.it',
        '+39 035 7890123',
        'Via Bergamo 156, 24122 Bergamo (BG)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Farmaceutica Delta',
        'ordini@farmaceutica-delta.it',
        '+39 010 8901234',
        'Via Genova 78, 16121 Genova (GE)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Automotive Parts Italia',
        'vendite@auto-parts.it',
        '+39 049 9012345',
        'Corso Padova 234, 35122 Padova (PD)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Green Energy Solutions',
        'info@green-energy.com',
        '+39 0471 0123456',
        'Via Bolzano 89, 39100 Bolzano (BZ)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Bar Centrale di Rossi Marco',
        'marco.rossi@barcentrale.it',
        '+39 347 1234567',
        'Piazza Centrale 5, 20010 Cornaredo (MI)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Pizzeria Da Luigi',
        'luigi@pizzeria-daluigi.it',
        '+39 348 2345678',
        'Via Giuseppe Verdi 23, 40137 Bologna (BO)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Ferramenta Bianchi',
        'info@ferramenta-bianchi.com',
        '+39 349 3456789',
        'Via Roma 134, 50125 Firenze (FI)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Studio Dentistico Dott. Verdi',
        'segreteria@studio-verdi.it',
        '+39 350 4567890',
        'Corso Italia 67, 10128 Torino (TO)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Parrucchiera Glamour',
        'appuntamenti@glamour.it',
        '+39 351 5678901',
        'Via della Moda 12, 20121 Milano (MI)',
        GETDATE(),
        GETDATE()
    ),
    (
        'Swiss Precision AG',
        'orders@swiss-precision.ch',
        '+41 44 1234567',
        'Bahnhofstrasse 15, 8001 Zürich, Switzerland',
        GETDATE(),
        GETDATE()
    ),
    (
        'French Cuisine SARL',
        'commandes@french-cuisine.fr',
        '+33 1 42345678',
        '15 Rue de la Paix, 75001 Paris, France',
        GETDATE(),
        GETDATE()
    ),
    (
        'German Engineering GmbH',
        'bestellungen@german-eng.de',
        '+49 30 3456789',
        'Unter den Linden 42, 10117 Berlin, Germany',
        GETDATE(),
        GETDATE()
    ),
    (
        'Startup Innovativa',
        'hello@startup-innovativa.com',
        NULL,
        'Via Innovation 1, 20900 Monza (MB)',
        GETDATE(),
        GETDATE()
    ),
    (
        'E-commerce Solutions',
        'support@ecommerce-sol.it',
        NULL,
        NULL,
        GETDATE(),
        GETDATE()
    ),
    (
        'Cliente Test S.r.l.',
        'test@cliente-esempio.com',
        '+39 333 0000000',
        'Via Test 123, 00100 Roma (RM)',
        GETDATE(),
        GETDATE()
    );