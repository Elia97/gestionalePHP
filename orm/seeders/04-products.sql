-- Seeder per prodotti

INSERT INTO
    dbo.products (
        code,
        name,
        description,
        price,
        category,
        created_at,
        updated_at
    )
VALUES (
        'COMP001',
        'Desktop PC i7',
        'Computer desktop con processore Intel i7, 16GB RAM, 512GB SSD',
        899.99,
        'informatica',
        GETDATE(),
        GETDATE()
    ),
    (
        'COMP002',
        'Laptop Gaming',
        'Laptop da gaming con RTX 4060, 32GB RAM, 1TB SSD',
        1299.99,
        'informatica',
        GETDATE(),
        GETDATE()
    ),
    (
        'COMP003',
        'Server Rack',
        'Server rack 1U con doppio Xeon, 64GB RAM',
        2499.99,
        'informatica',
        GETDATE(),
        GETDATE()
    ),
    (
        'COMP004',
        'Workstation Pro',
        'Workstation professionale per CAD/3D',
        1899.99,
        'informatica',
        GETDATE(),
        GETDATE()
    ),
    (
        'COMP005',
        'Mini PC',
        'Mini PC compatto per ufficio',
        349.99,
        'informatica',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC001',
        'Tastiera Meccanica',
        'Tastiera meccanica RGB con switch Cherry MX',
        89.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC002',
        'Mouse Gaming',
        'Mouse gaming wireless con DPI regolabile',
        59.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC003',
        'Webcam 4K',
        'Webcam 4K per videoconferenze professionali',
        129.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC004',
        'Hub USB-C',
        'Hub USB-C con 7 porte e alimentazione',
        49.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC005',
        'Supporto Monitor',
        'Supporto regolabile per monitor fino a 32 pollici',
        39.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'ACC006',
        'Mousepad XXL',
        'Mousepad da gaming extra large 900x400mm',
        24.99,
        'accessori',
        GETDATE(),
        GETDATE()
    ),
    (
        'MON001',
        'Monitor 24" 4K',
        'Monitor 24 pollici 4K IPS per professioni grafiche',
        399.99,
        'monitor',
        GETDATE(),
        GETDATE()
    ),
    (
        'MON002',
        'Monitor Gaming 27"',
        'Monitor gaming 27" 144Hz G-Sync',
        449.99,
        'monitor',
        GETDATE(),
        GETDATE()
    ),
    (
        'MON003',
        'Monitor Ultrawide',
        'Monitor ultrawide 34" curvo per produttività',
        599.99,
        'monitor',
        GETDATE(),
        GETDATE()
    ),
    (
        'MON004',
        'Monitor Touch 22"',
        'Monitor touchscreen 22" per applicazioni interattive',
        289.99,
        'monitor',
        GETDATE(),
        GETDATE()
    ),
    (
        'MON005',
        'Monitor Dual 24"',
        'Set di due monitor 24" Full HD con supporto',
        449.99,
        'monitor',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO001',
        'SSD 1TB NVMe',
        'SSD NVMe M.2 1TB ad alte prestazioni',
        89.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO002',
        'HDD 4TB',
        'Hard disk tradizionale 4TB per archiviazione',
        99.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO003',
        'SSD Esterno 2TB',
        'SSD esterno portatile 2TB USB-C',
        199.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO004',
        'NAS 4 Bay',
        'Network Attached Storage 4 bay con RAID',
        299.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO005',
        'Micro SD 128GB',
        'Scheda microSD 128GB classe 10',
        19.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'STO006',
        'USB Drive 64GB',
        'Chiavetta USB 3.0 64GB resistente all''acqua',
        14.99,
        'storage',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET001',
        'Router WiFi 6',
        'Router WiFi 6 dual band con 4 antenne',
        149.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET002',
        'Switch Gigabit 24p',
        'Switch managed gigabit 24 porte',
        199.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET003',
        'Access Point WiFi',
        'Access point WiFi professionale PoE',
        89.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET004',
        'Cavo Ethernet Cat6',
        'Cavo ethernet Cat6 da 10 metri',
        12.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET005',
        'Firewall Hardware',
        'Firewall hardware per piccole reti',
        249.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'NET006',
        'Powerline Adapter',
        'Adattatore powerline 1200Mbps kit da 2',
        59.99,
        'networking',
        GETDATE(),
        GETDATE()
    ),
    (
        'AUD001',
        'Cuffie Gaming',
        'Cuffie gaming con microfono e surround 7.1',
        79.99,
        'audio',
        GETDATE(),
        GETDATE()
    ),
    (
        'AUD002',
        'Speakers 2.1',
        'Sistema audio 2.1 con subwoofer',
        89.99,
        'audio',
        GETDATE(),
        GETDATE()
    ),
    (
        'AUD003',
        'Microfono USB',
        'Microfono USB professionale per streaming',
        129.99,
        'audio',
        GETDATE(),
        GETDATE()
    ),
    (
        'AUD004',
        'Soundbar',
        'Soundbar wireless per PC e TV',
        149.99,
        'audio',
        GETDATE(),
        GETDATE()
    ),
    (
        'AUD005',
        'Auricolari Bluetooth',
        'Auricolari true wireless con cancellazione rumore',
        199.99,
        'audio',
        GETDATE(),
        GETDATE()
    );