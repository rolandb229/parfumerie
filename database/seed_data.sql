-- =====================================================
-- TATAVERNIS - Données de Démonstration
-- Script d'insertion pour peupler la base de données
-- =====================================================

USE tatavernis;

-- =====================================================
-- INSERTION: Administrateurs
-- =====================================================
INSERT INTO admins (username, email, password, first_name, last_name, role, status) VALUES
('admin', 'admin@tatavernis.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'TATAVERNIS', 'super_admin', 'active'),
('manager', 'manager@tatavernis.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marie', 'Koné', 'admin', 'active'),
('editor', 'editor@tatavernis.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Kouassi', 'editor', 'active');

-- =====================================================
-- INSERTION: Utilisateurs (Clients)
-- =====================================================
INSERT INTO users (first_name, last_name, email, phone, password, gender, newsletter, email_verified, loyalty_points, total_spent, orders_count, status) VALUES
('Aminata', 'Diallo', 'aminata.diallo@email.com', '+225 07 12 34 56 78', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', TRUE, TRUE, 2500, 850000.00, 12, 'active'),
('Kouadio', 'Yao', 'kouadio.yao@email.com', '+225 05 98 76 54 32', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', TRUE, TRUE, 1800, 520000.00, 8, 'active'),
('Fatou', 'Traoré', 'fatou.traore@email.com', '+225 01 23 45 67 89', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', FALSE, TRUE, 950, 285000.00, 5, 'active'),
('Ibrahim', 'Konaté', 'ibrahim.konate@email.com', '+225 07 65 43 21 09', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', TRUE, TRUE, 3200, 1250000.00, 18, 'active'),
('Mariam', 'Bamba', 'mariam.bamba@email.com', '+225 05 11 22 33 44', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', TRUE, TRUE, 1500, 420000.00, 7, 'active'),
('Seydou', 'Coulibaly', 'seydou.coulibaly@email.com', '+225 01 55 66 77 88', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', FALSE, TRUE, 750, 195000.00, 3, 'active'),
('Aïcha', 'Sanogo', 'aicha.sanogo@email.com', '+225 07 99 88 77 66', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', TRUE, TRUE, 2100, 680000.00, 10, 'active'),
('Moussa', 'Touré', 'moussa.toure@email.com', '+225 05 44 33 22 11', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', TRUE, TRUE, 1200, 350000.00, 6, 'active');

-- =====================================================
-- INSERTION: Catégories
-- =====================================================
INSERT INTO categories (name, slug, description, image, parent_id, sort_order, is_featured, status) VALUES
('Parfums Homme', 'parfums-homme', 'Collection exclusive de parfums masculins alliant élégance et caractère', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/d44862b49c8647ea8fb54392986922b3-KGFbjgB9CUJTMtRpBJISDcm3EqvLu1.jpg', NULL, 1, TRUE, 'active'),
('Parfums Femme', 'parfums-femme', 'Fragrances féminines raffinées pour sublimer votre personnalité', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/bc1c135eb1ee437eb5f3891eb895cb49-aAOGLN3FGMxopKzQpsykkqSkUILhtq.jpg', NULL, 2, TRUE, 'active'),
('Parfums Unisexe', 'parfums-unisexe', 'Créations olfactives universelles transcendant les genres', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg', NULL, 3, TRUE, 'active'),
('Extraits de Parfum', 'extraits-parfum', 'Les concentrations les plus intenses pour une présence remarquable', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg', NULL, 4, TRUE, 'active'),
('Coffrets Cadeaux', 'coffrets-cadeaux', 'Ensembles prestigieux parfaits pour offrir', NULL, NULL, 5, TRUE, 'active'),
('Nouveautés', 'nouveautes', 'Découvrez nos dernières créations exclusives', NULL, NULL, 6, TRUE, 'active'),
('Best-Sellers', 'best-sellers', 'Les parfums les plus appréciés par notre clientèle', NULL, NULL, 7, TRUE, 'active'),
('Orientaux', 'orientaux', 'Fragrances chaudes et envoûtantes inspirées d\'Orient', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/b0cef2e426fb435db0d1b838bec8ee65-0kmnoaZBSauEpGDYZ1PrSYNDDNX9sd.jpg', NULL, 8, FALSE, 'active'),
('Boisés', 'boises', 'Notes boisées nobles et sophistiquées', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/c366796e43f2410198c33fc2a78c796d-SlhVCs8aBs7b2BlEiA1PoVBJtsSdjH.jpg', NULL, 9, FALSE, 'active'),
('Frais', 'frais', 'Compositions légères et vivifiantes', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/8cbb799e34a942fa8d7a4b349154d546-PxDbuk4kJv6D2TGrH8vcPSWAbjzlVS.jpg', NULL, 10, FALSE, 'active');

-- =====================================================
-- INSERTION: Produits
-- =====================================================
INSERT INTO products (sku, name, slug, short_description, description, category_id, brand, price, compare_price, stock_quantity, genre, target, concentration, notes_top, notes_heart, notes_base, sizes, main_image, is_featured, is_new, is_bestseller, status, views_count, sales_count) VALUES

-- PRODUIT 1: Baccarat Rouge 540
('TV-BR540-001', 'Baccarat Rouge 540', 'baccarat-rouge-540', 
'Un chef-d\'œuvre olfactif aux notes ambrées et florales, signature de l\'excellence française',
'<p>Baccarat Rouge 540 est une création emblématique de la Maison Francis Kurkdjian. Ce parfum d\'exception marie avec subtilité les notes de safran et de jasmin à un fond boisé ambré d\'une rare élégance.</p><p>Son sillage envoûtant et sa tenue exceptionnelle en font un incontournable pour les amateurs de haute parfumerie.</p>',
4, 'Maison Francis Kurkdjian', 185000.00, 220000.00, 25, 'ambré', 'unisexe', 'extrait',
'Safran, Jasmin d\'Égypte', 'Ambroxan, Fir Resin', 'Cèdre de Virginie, Ambre gris',
'[{"size": "35ml", "price": 125000}, {"size": "70ml", "price": 185000}, {"size": "200ml", "price": 350000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg',
TRUE, TRUE, TRUE, 'active', 15420, 342),

-- PRODUIT 2: Le Male Le Parfum
('TV-LMLP-002', 'Le Male Le Parfum', 'le-male-le-parfum',
'L\'incarnation de la masculinité moderne, intense et séduisant',
'<p>Jean Paul Gaultier réinvente son classique avec Le Male Le Parfum. Cette version intensifiée combine la fraîcheur de la cardamome avec la chaleur de la vanille et du tonka.</p><p>Un parfum viril et sophistiqué qui laisse un sillage inoubliable.</p>',
1, 'Jean Paul Gaultier', 75000.00, 95000.00, 42, 'oriental', 'homme', 'edp',
'Cardamome, Iris, Menthe', 'Lavande, Fève Tonka', 'Vanille, Bois de Santal, Cuir',
'[{"size": "75ml", "price": 75000}, {"size": "125ml", "price": 95000}, {"size": "200ml", "price": 125000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/d44862b49c8647ea8fb54392986922b3-KGFbjgB9CUJTMtRpBJISDcm3EqvLu1.jpg',
TRUE, FALSE, TRUE, 'active', 12850, 456),

-- PRODUIT 3: Épices Exquises
('TV-EE-003', 'Épices Exquises', 'epices-exquises',
'Un voyage sensoriel aux confins des routes des épices',
'<p>Guerlain nous invite à un périple olfactif avec Épices Exquises. Cette fragrance orientale capture l\'essence même des marchés d\'épices d\'Orient.</p><p>Le poivre rose et la cannelle s\'entremêlent avec le musc et le bois de oud pour créer une symphonie enivrante.</p>',
8, 'Guerlain', 145000.00, NULL, 18, 'épicé', 'unisexe', 'edp',
'Poivre rose, Cannelle, Safran', 'Cardamome, Rose de Damas, Géranium', 'Oud, Musc blanc, Vanille',
'[{"size": "125ml", "price": 145000}, {"size": "200ml", "price": 195000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/87265b3f0f06480e8c8a6f970d5a62e8-g6EquQdf1eL1B60Odyxf5W24QsaouX.jpg',
TRUE, TRUE, FALSE, 'active', 8920, 187),

-- PRODUIT 4: Pannaco Tahaa Curd Mango
('TV-PTCM-004', 'Pannaco Tahaa Curd Mango', 'pannaco-tahaa-curd-mango',
'Une explosion tropicale de mangue crémeuse et de vanille tahitienne',
'<p>Fomowa Paris présente une création audacieuse avec Pannaco Tahaa. Ce parfum gourmand capture l\'essence de la mangue Mahachanok dans toute sa splendeur.</p><p>Un voyage olfactif entre les îles du Pacifique et la sophistication parisienne.</p>',
3, 'Fomowa Paris', 125000.00, 150000.00, 30, 'fruité', 'unisexe', 'extrait',
'Mangue Mahachanok, Bergamote, Poire', 'Fleur de Tiaré, Lait de Coco, Jasmin', 'Vanille de Tahiti, Santal, Musc',
'[{"size": "50ml", "price": 85000}, {"size": "100ml", "price": 125000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg',
TRUE, TRUE, FALSE, 'active', 10250, 234),

-- PRODUIT 5: Bois d'Argent
('TV-BDA-005', 'Bois d\'Argent', 'bois-dargent',
'L\'élégance du bois précieux sublimée par l\'iris florentin',
'<p>Christian Dior signe une œuvre magistrale avec Bois d\'Argent. Cette fragrance boisée d\'une rare sophistication mêle les notes d\'iris à l\'encens et au miel.</p><p>Un parfum noble et mystérieux, parfait pour les connaisseurs.</p>',
9, 'Christian Dior', 195000.00, NULL, 15, 'boisé', 'unisexe', 'edp',
'Encens, Miel, Néroli', 'Iris Pallida, Rose Centifolia', 'Bois de Santal, Musc blanc, Ambre',
'[{"size": "125ml", "price": 195000}, {"size": "250ml", "price": 295000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/c366796e43f2410198c33fc2a78c796d-SlhVCs8aBs7b2BlEiA1PoVBJtsSdjH.jpg',
TRUE, FALSE, TRUE, 'active', 7650, 156),

-- PRODUIT 6: Habit Rouge
('TV-HR-006', 'Habit Rouge', 'habit-rouge',
'Le classique intemporel de la parfumerie masculine française',
'<p>Habit Rouge de Guerlain est une légende vivante de la parfumerie. Créé en 1965, ce parfum incarne l\'élégance masculine dans toute sa splendeur.</p><p>Ses notes d\'agrumes, de cuir et de vanille créent une signature olfactive inoubliable.</p>',
1, 'Guerlain', 85000.00, 105000.00, 38, 'oriental', 'homme', 'edp',
'Orange, Citron, Bergamote', 'Œillet, Cannelle, Patchouli', 'Cuir, Vanille, Benjoin, Ambre',
'[{"size": "50ml", "price": 65000}, {"size": "100ml", "price": 85000}, {"size": "200ml", "price": 135000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/e984d8fe99064904a95b5223dbccefa2-BIcZz9CUszxOeRDcLIC193koZQ3FvP.jpg',
FALSE, FALSE, TRUE, 'active', 11200, 389),

-- PRODUIT 7: Prada Luna Rossa Ocean
('TV-PLRO-007', 'Prada Luna Rossa Ocean', 'prada-luna-rossa-ocean',
'L\'aventure aquatique incarnée dans un flacon iconique',
'<p>Prada réinterprète la fraîcheur maritime avec Luna Rossa Ocean. Ce parfum capture l\'essence de l\'océan infini et l\'esprit d\'aventure.</p><p>Une composition aromatique aquatique qui évoque la liberté et le dépassement de soi.</p>',
10, 'Prada', 78000.00, NULL, 45, 'aquatique', 'homme', 'edt',
'Bergamote, Iris, Notes Ozuniques', 'Lavande, Vétiver, Sauge', 'Musc, Ambre, Bois de Cèdre',
'[{"size": "50ml", "price": 55000}, {"size": "100ml", "price": 78000}, {"size": "150ml", "price": 98000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/8cbb799e34a942fa8d7a4b349154d546-PxDbuk4kJv6D2TGrH8vcPSWAbjzlVS.jpg',
TRUE, TRUE, FALSE, 'active', 9870, 278),

-- PRODUIT 8: Vanilla Extasy
('TV-VE-008', 'Vanilla Extasy', 'vanilla-extasy',
'L\'extase vanillée dans sa forme la plus pure et sensuelle',
'<p>Montale Paris célèbre la vanille sous toutes ses facettes avec Vanilla Extasy. Un parfum gourmand et enveloppant qui réchauffe l\'âme.</p><p>L\'abricot juteux se mêle à la vanille de Madagascar pour créer une addiction olfactive.</p>',
2, 'Montale Paris', 95000.00, 115000.00, 28, 'gourmand', 'femme', 'edp',
'Abricot, Pêche, Bergamote', 'Orchidée, Jasmin, Fleur d\'Oranger', 'Vanille de Madagascar, Santal, Musc blanc',
'[{"size": "50ml", "price": 70000}, {"size": "100ml", "price": 95000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/6c7c4cf8d5004a3cb5978051c0e72619-lHiXupzFtFACk4diBcDz39S6eBC6FO.jpg',
TRUE, FALSE, TRUE, 'active', 13400, 412),

-- PRODUIT 9: Kayali Oudgasm
('TV-KO-009', 'Kayali Oudgasm', 'kayali-oudgasm',
'Le mariage parfait entre le oud et la rose dans un écrin de luxe',
'<p>Kayali nous offre une interprétation moderne de l\'accord oud-rose avec Oudgasm. Cette création allie tradition orientale et modernité.</p><p>Un parfum addictif qui capture l\'essence du luxe moyen-oriental.</p>',
8, 'Kayali', 135000.00, NULL, 22, 'oriental', 'unisexe', 'edp',
'Rose de Damas, Safran, Cappuccino', 'Oud, Géranium, Jasmin Sambac', 'Vanille, Musc, Oud précieux',
'[{"size": "50ml", "price": 95000}, {"size": "100ml", "price": 135000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/b0cef2e426fb435db0d1b838bec8ee65-0kmnoaZBSauEpGDYZ1PrSYNDDNX9sd.jpg',
TRUE, TRUE, FALSE, 'active', 8760, 198),

-- PRODUIT 10: Nishane Fan Your Flames
('TV-NFYF-010', 'Nishane Fan Your Flames', 'nishane-fan-your-flames',
'L\'audace turque dans une composition riche et captivante',
'<p>Nishane repousse les limites de la créativité avec Fan Your Flames. Cette fragrance épicée et boisée est une déclaration d\'individualité.</p><p>La noix de coco se mêle à la cannelle et au cèdre pour créer une chaleur envoûtante.</p>',
4, 'Nishane', 165000.00, 195000.00, 16, 'épicé', 'unisexe', 'extrait',
'Cannelle, Gingembre, Noix de Coco', 'Datte, Café, Rose épicée', 'Cèdre, Vanille, Ambre, Benjoin',
'[{"size": "50ml", "price": 125000}, {"size": "100ml", "price": 165000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/2ef5b90dfde948ffb3e1530789d082fb-gjOe4eWtHDH3acg4ZSUd6jekDjIk2l.jpg',
TRUE, TRUE, TRUE, 'active', 7230, 145),

-- PRODUIT 11: Azzaro Wanted
('TV-AW-011', 'Azzaro Wanted', 'azzaro-wanted',
'Le parfum de l\'homme désiré, audacieux et magnétique',
'<p>Azzaro Wanted est le parfum de l\'homme qui ose. Sa composition boisée épicée irradie la confiance et le charisme.</p><p>Le gingembre et le citron ouvrent sur un cœur de cardamome avant de révéler un fond de vétiver et de tonka.</p>',
1, 'Azzaro', 55000.00, 70000.00, 55, 'boisé', 'homme', 'edt',
'Citron, Gingembre, Lavande', 'Cardamome, Genévrier, Pomme', 'Vétiver, Fève Tonka, Bois de Cèdre',
'[{"size": "50ml", "price": 45000}, {"size": "100ml", "price": 55000}, {"size": "150ml", "price": 75000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/02bb9237802642b8bada482a8caac521-IyqOxj8a77T0SAE7GHa7L2XJhhQ5Os.jpg',
FALSE, FALSE, TRUE, 'active', 14560, 523),

-- PRODUIT 12: Bad Boy Cobalt
('TV-BBC-012', 'Bad Boy Cobalt', 'bad-boy-cobalt',
'L\'énergie électrique du mauvais garçon assumé',
'<p>Carolina Herrera électrise la parfumerie masculine avec Bad Boy Cobalt. Cette version intensifiée capture l\'essence de la rébellion élégante.</p><p>Un parfum audacieux qui combine fraîcheur aquatique et sensualité épicée.</p>',
1, 'Carolina Herrera', 68000.00, 85000.00, 35, 'épicé', 'homme', 'edp',
'Bergamote, Feuille de Sauge, Ozunique', 'Cardamome, Iris, Noix de Muscade', 'Cèdre, Ambre, Cuir, Tonka',
'[{"size": "50ml", "price": 52000}, {"size": "100ml", "price": 68000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/42aaf7efaed1482796e57050a34ccb8d-tRCR0zd8tdP8t9CqBVIwanVigGhfMj.jpg',
TRUE, TRUE, FALSE, 'active', 9450, 267),

-- PRODUIT 13: Pasha de Cartier Edition Noire
('TV-PCN-013', 'Pasha de Cartier Édition Noire', 'pasha-cartier-edition-noire',
'L\'élégance parisienne dans sa version la plus intense',
'<p>Cartier réinvente son classique avec Pasha Édition Noire. Cette version nocturne capture la sophistication masculine ultime.</p><p>Un parfum boisé aromatique parfait pour l\'homme raffiné qui cultive le mystère.</p>',
1, 'Cartier', 92000.00, 115000.00, 26, 'boisé', 'homme', 'edt',
'Citron, Mandarine, Menthe', 'Lavande, Patchouli, Bois de Rose', 'Santal, Cèdre, Ambre gris, Musc',
'[{"size": "50ml", "price": 72000}, {"size": "100ml", "price": 92000}, {"size": "150ml", "price": 125000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/19e1b43670244fb0ab03ea785067c22c-TMG3ea54BujC3ZaDscIE4VFlDSF3Fe.jpg',
TRUE, FALSE, TRUE, 'active', 8120, 234),

-- PRODUIT 14: Vanille Sauvage
('TV-VS-014', 'Vanille Sauvage', 'vanille-sauvage',
'La vanille dans son expression la plus authentique et sauvage',
'<p>Cette création exclusive célèbre la vanille dans toute sa richesse naturelle. Un parfum enveloppant qui évoque les plantations tropicales.</p><p>Notes gourmandes et boisées se marient pour créer une fragrance addictive.</p>',
2, 'TATAVERNIS Exclusive', 75000.00, NULL, 40, 'gourmand', 'femme', 'edp',
'Bergamote, Mandarine, Gingembre', 'Vanille, Héliotrope, Jasmin', 'Fève Tonka, Santal, Musc blanc',
'[{"size": "50ml", "price": 55000}, {"size": "100ml", "price": 75000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/239c8111e948412fa59d95a396af3f12-NczzLz7UUkTUwgcyfi6TQqXCa89a1v.jpg',
TRUE, TRUE, FALSE, 'active', 11230, 345),

-- PRODUIT 15: Montale Vanille Extasy Orchid
('TV-MVEO-015', 'Montale Vanille Orchidée', 'montale-vanille-orchidee',
'L\'orchidée blanche dansant avec la vanille crémeuse',
'<p>Montale nous offre une variation florale de sa célèbre Vanilla Extasy. L\'orchidée blanche apporte fraîcheur et élégance.</p><p>Un parfum féminin par excellence, romantique et séduisant.</p>',
2, 'Montale Paris', 98000.00, 120000.00, 32, 'floral', 'femme', 'edp',
'Poire, Bergamote, Groseille', 'Orchidée blanche, Rose, Pivoine', 'Vanille, Musc, Bois de Santal',
'[{"size": "50ml", "price": 72000}, {"size": "100ml", "price": 98000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/bc1c135eb1ee437eb5f3891eb895cb49-aAOGLN3FGMxopKzQpsykkqSkUILhtq.jpg',
TRUE, FALSE, TRUE, 'active', 10870, 298),

-- PRODUIT 16: Pannaco Tahaa Tropical
('TV-PTT-016', 'Pannaco Tahaa Tropical Edition', 'pannaco-tahaa-tropical-edition',
'Le soleil des tropiques capturé dans un flacon d\'exception',
'<p>Fomowa Paris présente une édition spéciale de sa collection Pannaco Tahaa. Cette version tropicale intensifie les notes fruitées.</p><p>Un parfum joyeux qui évoque les plages de sable blanc et les fruits juteux.</p>',
3, 'Fomowa Paris', 135000.00, 165000.00, 20, 'fruité', 'unisexe', 'extrait',
'Mangue, Ananas, Citron vert', 'Fleur de Frangipanier, Tiaré, Ylang-Ylang', 'Noix de Coco, Santal, Musc',
'[{"size": "50ml", "price": 95000}, {"size": "100ml", "price": 135000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/6d16b40a852f42cea2f0c22c2424b863-UFRwyX3ZlCKRe5FtNFSjRC8OE9R03v.jpg',
TRUE, TRUE, FALSE, 'active', 7650, 156),

-- PRODUIT 17: Nishane Fan Your Flames Box Set
('TV-NFYFB-017', 'Nishane Fan Your Flames Coffret', 'nishane-fan-your-flames-coffret',
'Le coffret prestige pour les amateurs de Nishane',
'<p>Découvrez Fan Your Flames dans un coffret luxueux comprenant le parfum 100ml et une version voyage 15ml.</p><p>L\'emballage doré met en valeur cette création d\'exception, parfait pour offrir.</p>',
5, 'Nishane', 195000.00, 225000.00, 12, 'épicé', 'unisexe', 'extrait',
'Cannelle, Gingembre, Noix de Coco', 'Datte, Café, Rose épicée', 'Cèdre, Vanille, Ambre, Benjoin',
'[{"size": "Coffret 100ml + 15ml", "price": 195000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/3c709b7f343b4c9f95e07083bdc8467f-CyHf7flwBMxkJk7dif4PZh0u8WAdOl.jpg',
TRUE, TRUE, FALSE, 'active', 4320, 89),

-- PRODUIT 18: Pannaco Golden Hour
('TV-PGH-018', 'Pannaco Golden Hour', 'pannaco-golden-hour',
'L\'heure dorée capturée dans chaque goutte précieuse',
'<p>Cette création Fomowa Paris célèbre la magie de l\'heure dorée. Un parfum chaleureux qui évoque les couchers de soleil sur l\'océan.</p><p>La mangue se mêle à la vanille pour créer une symphonie lumineuse.</p>',
3, 'Fomowa Paris', 145000.00, NULL, 18, 'gourmand', 'unisexe', 'extrait',
'Mangue, Néroli, Bergamote', 'Jasmin, Tubéreuse, Frangipani', 'Vanille, Ambre, Bois précieux',
'[{"size": "100ml", "price": 145000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/988c7bf5394f4c4eafe7173290815d5c-7esXmxhKEE070H9a2rPFP6yeYRwu2e.jpg',
TRUE, TRUE, FALSE, 'active', 6540, 123),

-- PRODUIT 19: Fruity Explosion
('TV-FE-019', 'Fruity Explosion', 'fruity-explosion',
'Une explosion de fruits juteux pour illuminer vos journées',
'<p>Ce parfum audacieux capture l\'énergie des fruits frais dans une composition vibrante. Banane, fraise et citron créent un cocktail olfactif irrésistible.</p><p>Parfait pour ceux qui osent sortir des sentiers battus.</p>',
6, 'TATAVERNIS Exclusive', 65000.00, 80000.00, 45, 'fruité', 'femme', 'edt',
'Banane, Fraise, Citron', 'Pêche, Grenade, Noix de Coco', 'Vanille, Musc blanc, Santal',
'[{"size": "50ml", "price": 48000}, {"size": "100ml", "price": 65000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/f1ce449530b14fc8913f9c96bd3af121-fr5dzcaaGbZmNRGXrHmDUnSaxbv24m.jpg',
TRUE, TRUE, FALSE, 'active', 8970, 234),

-- PRODUIT 20: Pannaco Tahaa Premium
('TV-PTP-020', 'Pannaco Tahaa Premium', 'pannaco-tahaa-premium',
'L\'édition premium avec coffret luxueux blanc nacré',
'<p>La version ultime de Pannaco Tahaa dans un écrin nacré d\'une élégance rare. Cette édition premium offre une concentration supérieure.</p><p>Le packaging blanc et or reflète la pureté des ingrédients sélectionnés.</p>',
4, 'Fomowa Paris', 175000.00, 210000.00, 15, 'fruité', 'unisexe', 'extrait',
'Mangue Mahachanok, Pamplemousse rose', 'Fleur de Tiaré, Jasmin Sambac', 'Vanille Bourbon, Bois de Oud, Musc',
'[{"size": "100ml avec coffret", "price": 175000}]',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/5f4089867ad94a4bbcbd04e3b3bd655e-SAHwk5TewIbJYSvgBDQVK5c0fIQiDI.jpg',
TRUE, TRUE, TRUE, 'active', 5890, 98);

-- =====================================================
-- INSERTION: Images produits supplémentaires
-- =====================================================
INSERT INTO product_images (product_id, image_path, alt_text, sort_order, is_primary) VALUES
(1, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg', 'Baccarat Rouge 540 - Vue principale', 1, TRUE),
(2, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/d44862b49c8647ea8fb54392986922b3-KGFbjgB9CUJTMtRpBJISDcm3EqvLu1.jpg', 'Le Male Le Parfum - Vue principale', 1, TRUE),
(3, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/87265b3f0f06480e8c8a6f970d5a62e8-g6EquQdf1eL1B60Odyxf5W24QsaouX.jpg', 'Épices Exquises - Vue principale', 1, TRUE),
(4, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg', 'Pannaco Tahaa - Vue principale', 1, TRUE),
(4, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/6d16b40a852f42cea2f0c22c2424b863-UFRwyX3ZlCKRe5FtNFSjRC8OE9R03v.jpg', 'Pannaco Tahaa - Vue avec fruits', 2, FALSE),
(4, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/988c7bf5394f4c4eafe7173290815d5c-7esXmxhKEE070H9a2rPFP6yeYRwu2e.jpg', 'Pannaco Tahaa - Vue ambiante', 3, FALSE),
(5, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/c366796e43f2410198c33fc2a78c796d-SlhVCs8aBs7b2BlEiA1PoVBJtsSdjH.jpg', 'Bois d\'Argent - Vue principale', 1, TRUE),
(6, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/e984d8fe99064904a95b5223dbccefa2-BIcZz9CUszxOeRDcLIC193koZQ3FvP.jpg', 'Habit Rouge - Vue principale', 1, TRUE),
(7, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/8cbb799e34a942fa8d7a4b349154d546-PxDbuk4kJv6D2TGrH8vcPSWAbjzlVS.jpg', 'Prada Luna Rossa Ocean - Vue principale', 1, TRUE),
(8, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/6c7c4cf8d5004a3cb5978051c0e72619-lHiXupzFtFACk4diBcDz39S6eBC6FO.jpg', 'Vanilla Extasy - Vue principale', 1, TRUE),
(9, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/b0cef2e426fb435db0d1b838bec8ee65-0kmnoaZBSauEpGDYZ1PrSYNDDNX9sd.jpg', 'Kayali Oudgasm - Vue principale', 1, TRUE),
(10, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/2ef5b90dfde948ffb3e1530789d082fb-gjOe4eWtHDH3acg4ZSUd6jekDjIk2l.jpg', 'Nishane Fan Your Flames - Vue principale', 1, TRUE),
(11, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/02bb9237802642b8bada482a8caac521-IyqOxj8a77T0SAE7GHa7L2XJhhQ5Os.jpg', 'Azzaro Wanted - Vue principale', 1, TRUE),
(12, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/42aaf7efaed1482796e57050a34ccb8d-tRCR0zd8tdP8t9CqBVIwanVigGhfMj.jpg', 'Bad Boy Cobalt - Vue principale', 1, TRUE),
(13, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/19e1b43670244fb0ab03ea785067c22c-TMG3ea54BujC3ZaDscIE4VFlDSF3Fe.jpg', 'Pasha de Cartier - Vue principale', 1, TRUE),
(14, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/239c8111e948412fa59d95a396af3f12-NczzLz7UUkTUwgcyfi6TQqXCa89a1v.jpg', 'Vanille Sauvage - Vue principale', 1, TRUE),
(15, 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/bc1c135eb1ee437eb5f3891eb895cb49-aAOGLN3FGMxopKzQpsykkqSkUILhtq.jpg', 'Montale Vanille Orchidée - Vue principale', 1, TRUE);

-- =====================================================
-- INSERTION: Tags
-- =====================================================
INSERT INTO tags (name, slug) VALUES
('Best-Seller', 'best-seller'),
('Nouveau', 'nouveau'),
('Exclusif', 'exclusif'),
('Édition Limitée', 'edition-limitee'),
('Promo', 'promo'),
('Luxe', 'luxe'),
('Intense', 'intense'),
('Léger', 'leger'),
('Longue Tenue', 'longue-tenue'),
('Signature', 'signature'),
('Romantique', 'romantique'),
('Séduisant', 'seduisant'),
('Fresh', 'fresh'),
('Soir', 'soir'),
('Jour', 'jour');

-- =====================================================
-- INSERTION: Association Produits-Tags
-- =====================================================
INSERT INTO product_tags (product_id, tag_id) VALUES
(1, 1), (1, 6), (1, 9), (1, 10),
(2, 1), (2, 7), (2, 12), (2, 14),
(3, 2), (3, 6), (3, 7),
(4, 2), (4, 3), (4, 9),
(5, 6), (5, 10), (5, 9),
(6, 1), (6, 10), (6, 9),
(7, 2), (7, 13), (7, 15),
(8, 1), (8, 11), (8, 9),
(9, 6), (9, 7), (9, 14),
(10, 3), (10, 6), (10, 4),
(11, 1), (11, 12), (11, 15),
(12, 2), (12, 7), (12, 14),
(13, 6), (13, 10), (13, 14),
(14, 2), (14, 11), (14, 9),
(15, 1), (15, 11), (15, 9);

-- =====================================================
-- INSERTION: Adresses
-- =====================================================
INSERT INTO addresses (user_id, type, is_default, first_name, last_name, phone, address_line1, address_line2, city, state, postal_code, country) VALUES
(1, 'shipping', TRUE, 'Aminata', 'Diallo', '+225 07 12 34 56 78', 'Cocody Riviera Bonoumin', 'Rue des Jardins, Villa 12', 'Abidjan', 'Abidjan', '01 BP 1234', 'Côte d\'Ivoire'),
(1, 'billing', FALSE, 'Aminata', 'Diallo', '+225 07 12 34 56 78', 'Cocody Riviera Bonoumin', 'Rue des Jardins, Villa 12', 'Abidjan', 'Abidjan', '01 BP 1234', 'Côte d\'Ivoire'),
(2, 'shipping', TRUE, 'Kouadio', 'Yao', '+225 05 98 76 54 32', 'Marcory Zone 4C', 'Immeuble Harmony, Apt 5B', 'Abidjan', 'Abidjan', '17 BP 456', 'Côte d\'Ivoire'),
(3, 'shipping', TRUE, 'Fatou', 'Traoré', '+225 01 23 45 67 89', 'Plateau Cité Administrative', 'Tour B, 8ème étage', 'Abidjan', 'Abidjan', '01 BP 789', 'Côte d\'Ivoire'),
(4, 'shipping', TRUE, 'Ibrahim', 'Konaté', '+225 07 65 43 21 09', 'Deux Plateaux Vallons', 'Résidence Palmiers', 'Abidjan', 'Abidjan', '28 BP 321', 'Côte d\'Ivoire'),
(5, 'shipping', TRUE, 'Mariam', 'Bamba', '+225 05 11 22 33 44', 'Angré 8ème Tranche', 'Près du Carrefour Life', 'Abidjan', 'Abidjan', '06 BP 654', 'Côte d\'Ivoire');

-- =====================================================
-- INSERTION: Commandes
-- =====================================================
INSERT INTO orders (order_number, user_id, subtotal, shipping_cost, discount_amount, total, status, payment_status, payment_method, shipping_method, shipping_first_name, shipping_last_name, shipping_phone, shipping_address, shipping_city, shipping_country, created_at) VALUES
('TV-2024-000001', 1, 260000.00, 5000.00, 25000.00, 240000.00, 'delivered', 'paid', 'mobile_money', 'express', 'Aminata', 'Diallo', '+225 07 12 34 56 78', 'Cocody Riviera Bonoumin, Rue des Jardins', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 30 DAY)),
('TV-2024-000002', 2, 150000.00, 3000.00, 0.00, 153000.00, 'delivered', 'paid', 'card', 'standard', 'Kouadio', 'Yao', '+225 05 98 76 54 32', 'Marcory Zone 4C, Immeuble Harmony', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 25 DAY)),
('TV-2024-000003', 3, 185000.00, 0.00, 18500.00, 166500.00, 'delivered', 'paid', 'mobile_money', 'pickup', 'Fatou', 'Traoré', '+225 01 23 45 67 89', 'Plateau Cité Administrative', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 20 DAY)),
('TV-2024-000004', 4, 320000.00, 5000.00, 32000.00, 293000.00, 'shipped', 'paid', 'transfer', 'express', 'Ibrahim', 'Konaté', '+225 07 65 43 21 09', 'Deux Plateaux Vallons, Résidence Palmiers', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('TV-2024-000005', 5, 95000.00, 3000.00, 0.00, 98000.00, 'processing', 'paid', 'mobile_money', 'standard', 'Mariam', 'Bamba', '+225 05 11 22 33 44', 'Angré 8ème Tranche', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('TV-2024-000006', 1, 125000.00, 5000.00, 12500.00, 117500.00, 'confirmed', 'paid', 'card', 'express', 'Aminata', 'Diallo', '+225 07 12 34 56 78', 'Cocody Riviera Bonoumin', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('TV-2024-000007', 6, 75000.00, 3000.00, 0.00, 78000.00, 'pending', 'pending', 'cash', 'standard', 'Seydou', 'Coulibaly', '+225 01 55 66 77 88', 'Yopougon Maroc', 'Abidjan', 'Côte d\'Ivoire', NOW()),
('TV-2024-000008', 7, 185000.00, 0.00, 18500.00, 166500.00, 'delivered', 'paid', 'mobile_money', 'pickup', 'Aïcha', 'Sanogo', '+225 07 99 88 77 66', 'Cocody Angré', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 15 DAY)),
('TV-2024-000009', 8, 135000.00, 5000.00, 0.00, 140000.00, 'delivered', 'paid', 'card', 'express', 'Moussa', 'Touré', '+225 05 44 33 22 11', 'Treichville Gare', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 12 DAY)),
('TV-2024-000010', 2, 245000.00, 3000.00, 24500.00, 223500.00, 'processing', 'paid', 'mobile_money', 'standard', 'Kouadio', 'Yao', '+225 05 98 76 54 32', 'Marcory Zone 4C', 'Abidjan', 'Côte d\'Ivoire', DATE_SUB(NOW(), INTERVAL 3 DAY));

-- =====================================================
-- INSERTION: Articles de commande
-- =====================================================
INSERT INTO order_items (order_id, product_id, product_name, product_sku, product_image, size, quantity, unit_price, total_price) VALUES
(1, 1, 'Baccarat Rouge 540', 'TV-BR540-001', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg', '70ml', 1, 185000.00, 185000.00),
(1, 2, 'Le Male Le Parfum', 'TV-LMLP-002', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/d44862b49c8647ea8fb54392986922b3-KGFbjgB9CUJTMtRpBJISDcm3EqvLu1.jpg', '75ml', 1, 75000.00, 75000.00),
(2, 6, 'Habit Rouge', 'TV-HR-006', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/e984d8fe99064904a95b5223dbccefa2-BIcZz9CUszxOeRDcLIC193koZQ3FvP.jpg', '100ml', 1, 85000.00, 85000.00),
(2, 11, 'Azzaro Wanted', 'TV-AW-011', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/02bb9237802642b8bada482a8caac521-IyqOxj8a77T0SAE7GHa7L2XJhhQ5Os.jpg', '100ml', 1, 55000.00, 55000.00),
(3, 1, 'Baccarat Rouge 540', 'TV-BR540-001', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg', '70ml', 1, 185000.00, 185000.00),
(4, 5, 'Bois d\'Argent', 'TV-BDA-005', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/c366796e43f2410198c33fc2a78c796d-SlhVCs8aBs7b2BlEiA1PoVBJtsSdjH.jpg', '125ml', 1, 195000.00, 195000.00),
(4, 4, 'Pannaco Tahaa Curd Mango', 'TV-PTCM-004', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg', '100ml', 1, 125000.00, 125000.00),
(5, 8, 'Vanilla Extasy', 'TV-VE-008', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/6c7c4cf8d5004a3cb5978051c0e72619-lHiXupzFtFACk4diBcDz39S6eBC6FO.jpg', '100ml', 1, 95000.00, 95000.00),
(6, 4, 'Pannaco Tahaa Curd Mango', 'TV-PTCM-004', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg', '100ml', 1, 125000.00, 125000.00),
(7, 2, 'Le Male Le Parfum', 'TV-LMLP-002', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/d44862b49c8647ea8fb54392986922b3-KGFbjgB9CUJTMtRpBJISDcm3EqvLu1.jpg', '75ml', 1, 75000.00, 75000.00),
(8, 1, 'Baccarat Rouge 540', 'TV-BR540-001', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg', '70ml', 1, 185000.00, 185000.00),
(9, 9, 'Kayali Oudgasm', 'TV-KO-009', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/b0cef2e426fb435db0d1b838bec8ee65-0kmnoaZBSauEpGDYZ1PrSYNDDNX9sd.jpg', '100ml', 1, 135000.00, 135000.00),
(10, 3, 'Épices Exquises', 'TV-EE-003', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/87265b3f0f06480e8c8a6f970d5a62e8-g6EquQdf1eL1B60Odyxf5W24QsaouX.jpg', '125ml', 1, 145000.00, 145000.00),
(10, 15, 'Montale Vanille Orchidée', 'TV-MVEO-015', 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/bc1c135eb1ee437eb5f3891eb895cb49-aAOGLN3FGMxopKzQpsykkqSkUILhtq.jpg', '100ml', 1, 98000.00, 98000.00);

-- =====================================================
-- INSERTION: Avis clients
-- =====================================================
INSERT INTO reviews (product_id, user_id, order_id, rating, title, comment, pros, cons, is_verified_purchase, is_approved, helpful_count, created_at) VALUES
(1, 1, 1, 5, 'Un chef-d\'œuvre absolu !', 'Ce parfum est tout simplement exceptionnel. Le sillage est incroyable et la tenue dépasse mes attentes. Je reçois des compliments à chaque fois que je le porte.', 'Sillage puissant, Tenue exceptionnelle, Notes uniques', 'Prix élevé mais justifié', TRUE, TRUE, 45, DATE_SUB(NOW(), INTERVAL 28 DAY)),
(1, 4, 3, 5, 'Mon parfum signature', 'Après avoir testé de nombreux parfums, j\'ai enfin trouvé celui qui me correspond parfaitement. Baccarat Rouge 540 est devenu mon signature.', 'Originalité, Qualité des ingrédients, Flacon magnifique', NULL, TRUE, TRUE, 32, DATE_SUB(NOW(), INTERVAL 18 DAY)),
(2, 2, 2, 4, 'Très bon parfum masculin', 'Le Male Le Parfum est vraiment bien. C\'est sensuel et masculin. Parfait pour les sorties du soir.', 'Sensuel, Bonne tenue, Prix correct', 'Un peu trop sucré pour le jour', TRUE, TRUE, 28, DATE_SUB(NOW(), INTERVAL 23 DAY)),
(6, 2, 2, 5, 'Un classique indémodable', 'Habit Rouge reste l\'un des meilleurs parfums masculins. Élégant et sophistiqué.', 'Élégant, Classique, Bonne projection', NULL, TRUE, TRUE, 19, DATE_SUB(NOW(), INTERVAL 22 DAY)),
(8, 5, 5, 5, 'Addiction totale !', 'Cette Vanilla Extasy porte bien son nom. Je suis complètement accro ! Les notes de vanille sont gourmandes sans être écoeurantes.', 'Gourmand, Séduisant, Longue tenue', NULL, TRUE, TRUE, 36, NOW()),
(4, 1, 6, 4, 'Original et délicieux', 'Pannaco Tahaa est vraiment original avec ses notes de mangue. Très agréable pour l\'été.', 'Original, Frais, Fruité', 'Tenue moyenne en été', TRUE, TRUE, 15, NOW()),
(11, 3, NULL, 4, 'Bon rapport qualité-prix', 'Azzaro Wanted est un très bon choix pour débuter dans la parfumerie. Polyvalent et accessible.', 'Prix accessible, Polyvalent', 'Sillage un peu léger', FALSE, TRUE, 8, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(5, 4, 4, 5, 'L\'excellence Dior', 'Bois d\'Argent est une merveille. L\'iris est traité avec une finesse remarquable. Un parfum pour les connaisseurs.', 'Raffiné, Unique, Qualité Dior', 'Prix premium', TRUE, TRUE, 22, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(9, 8, 9, 5, 'Le oud parfait', 'Kayali Oudgasm est exactement ce que je cherchais. Un oud accessible mais de qualité, pas trop agressif.', 'Oud équilibré, Beau flacon, Bonne tenue', NULL, TRUE, TRUE, 18, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(10, 7, 8, 5, 'Une découverte !', 'Je ne connaissais pas Nishane et quelle découverte ! Fan Your Flames est épicé et chaleureux, parfait pour l\'hiver.', 'Épicé, Chaleureux, Original', 'Peut être trop puissant pour certains', TRUE, TRUE, 25, DATE_SUB(NOW(), INTERVAL 13 DAY));

-- =====================================================
-- INSERTION: Codes promo
-- =====================================================
INSERT INTO coupons (code, name, description, type, value, min_order_amount, max_discount_amount, usage_limit, usage_per_user, starts_at, expires_at, is_active) VALUES
('BIENVENUE15', 'Bienvenue -15%', '15% de réduction pour les nouveaux clients', 'percentage', 15.00, 50000.00, 30000.00, 500, 1, NOW(), DATE_ADD(NOW(), INTERVAL 6 MONTH), TRUE),
('LUXE20', 'Luxe -20%', '20% sur les extraits de parfum', 'percentage', 20.00, 100000.00, 50000.00, 100, 2, NOW(), DATE_ADD(NOW(), INTERVAL 3 MONTH), TRUE),
('LIVGRATUITE', 'Livraison Gratuite', 'Livraison offerte dès 75000 FCFA', 'free_shipping', 5000.00, 75000.00, 5000.00, NULL, 3, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), TRUE),
('VIP10', 'Client VIP -10%', '10% de réduction permanente pour clients VIP', 'percentage', 10.00, NULL, NULL, NULL, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), TRUE),
('FLASH25', 'Flash Sale -25%', 'Vente flash : 25% de réduction', 'percentage', 25.00, 80000.00, 40000.00, 50, 1, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), TRUE),
('NOEL2024', 'Noël 2024', 'Offre spéciale Noël : -30%', 'percentage', 30.00, 100000.00, 60000.00, 200, 1, '2024-12-01 00:00:00', '2024-12-31 23:59:59', TRUE),
('CADEAU5000', 'Bon de 5000F', 'Réduction fixe de 5000 FCFA', 'fixed', 5000.00, 30000.00, 5000.00, 300, 1, NOW(), DATE_ADD(NOW(), INTERVAL 2 MONTH), TRUE);

-- =====================================================
-- INSERTION: Promotions
-- =====================================================
INSERT INTO promotions (name, slug, description, discount_type, discount_value, starts_at, ends_at, is_active) VALUES
('Soldes d\'Été', 'soldes-ete', 'Jusqu\'à -30% sur une sélection de parfums', 'percentage', 30.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), TRUE),
('Black Friday', 'black-friday', 'Le plus grand événement shopping de l\'année', 'percentage', 40.00, '2024-11-25 00:00:00', '2024-11-30 23:59:59', FALSE),
('Fête des Mères', 'fete-meres', 'Offrez le meilleur à votre maman', 'percentage', 20.00, '2024-05-20 00:00:00', '2024-05-31 23:59:59', FALSE);

-- =====================================================
-- INSERTION: Produits en promotion
-- =====================================================
INSERT INTO promotion_products (promotion_id, product_id) VALUES
(1, 6), (1, 8), (1, 11), (1, 14), (1, 15);

-- =====================================================
-- INSERTION: Articles de blog
-- =====================================================
INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author_id, category, tags, views_count, likes_count, reading_time, is_featured, status, published_at, created_at) VALUES
('Comment choisir son parfum selon sa personnalité', 'comment-choisir-parfum-personnalite',
'Découvrez les secrets pour trouver la fragrance qui vous correspond parfaitement.',
'<p>Le choix d\'un parfum est une décision très personnelle qui reflète notre identité et notre style de vie. Dans cet article, nous vous guidons à travers les différentes familles olfactives pour vous aider à trouver votre signature olfactive idéale.</p><h2>Les familles olfactives</h2><p>Il existe plusieurs grandes familles : les floraux, les orientaux, les boisés, les frais et les chyprés. Chacune possède ses caractéristiques distinctives...</p>',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/973a019144b44920800d930eda4f5f01-P87UlzoDaZ4UkR0SjpOb0yRSMzbjW2.jpg',
1, 'Conseils', '["parfum", "guide", "conseils", "personnalité"]', 3250, 156, 8, TRUE, 'published', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),

('Les tendances parfums 2024 à ne pas manquer', 'tendances-parfums-2024',
'Tour d\'horizon des créations qui marqueront l\'année 2024.',
'<p>L\'année 2024 s\'annonce riche en nouveautés olfactives. Les maisons de parfumerie rivalisent de créativité pour nous surprendre avec des compositions audacieuses et innovantes.</p><h2>Le retour des gourmands</h2><p>Les parfums gourmands font un retour en force avec des notes de vanille, de café et de chocolat sublimées par des accords modernes...</p>',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg',
1, 'Tendances', '["tendances", "2024", "nouveautés"]', 2890, 134, 6, TRUE, 'published', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),

('L\'art de la pyramide olfactive expliqué', 'art-pyramide-olfactive',
'Comprenez enfin les notes de tête, de cœur et de fond.',
'<p>Chaque parfum est construit comme une pyramide avec trois niveaux de notes qui se révèlent progressivement sur la peau. Maîtriser ce concept vous permettra de mieux apprécier et choisir vos fragrances.</p><h2>Les notes de tête</h2><p>Ce sont les premières notes que vous sentez à l\'application. Légères et volatiles, elles s\'évaporent rapidement...</p>',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/87265b3f0f06480e8c8a6f970d5a62e8-g6EquQdf1eL1B60Odyxf5W24QsaouX.jpg',
2, 'Éducation', '["pyramide", "notes", "guide"]', 1950, 89, 10, FALSE, 'published', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

('Baccarat Rouge 540 : le mythe décrypté', 'baccarat-rouge-540-mythe-decrypte',
'Pourquoi ce parfum est-il devenu un phénomène mondial ?',
'<p>Depuis sa création en 2015, Baccarat Rouge 540 de Maison Francis Kurkdjian est devenu un véritable phénomène dans le monde de la parfumerie. Décryptage d\'un succès hors normes.</p><h2>Une composition révolutionnaire</h2><p>Francis Kurkdjian a créé une fragrance unique en combinant des ingrédients familiers de manière totalement nouvelle...</p>',
'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/49208ac133d5448789a5e132f6835933-pJOC8NfRhJunudxFuthQ5QZJYTslUq.jpg',
1, 'Découverte', '["baccarat rouge", "MFK", "luxe", "tendance"]', 4120, 245, 12, TRUE, 'published', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY));

-- =====================================================
-- INSERTION: Newsletter subscribers
-- =====================================================
INSERT INTO newsletter_subscribers (email, first_name, is_active, subscribed_at) VALUES
('marie.kouame@email.com', 'Marie', TRUE, DATE_SUB(NOW(), INTERVAL 60 DAY)),
('jean.aka@email.com', 'Jean', TRUE, DATE_SUB(NOW(), INTERVAL 45 DAY)),
('sarah.brou@email.com', 'Sarah', TRUE, DATE_SUB(NOW(), INTERVAL 30 DAY)),
('paul.koffi@email.com', 'Paul', TRUE, DATE_SUB(NOW(), INTERVAL 25 DAY)),
('aline.zadi@email.com', 'Aline', TRUE, DATE_SUB(NOW(), INTERVAL 20 DAY)),
('marc.ouattara@email.com', 'Marc', TRUE, DATE_SUB(NOW(), INTERVAL 15 DAY)),
('julie.djo@email.com', 'Julie', TRUE, DATE_SUB(NOW(), INTERVAL 10 DAY)),
('david.tanoh@email.com', 'David', TRUE, DATE_SUB(NOW(), INTERVAL 7 DAY)),
('claire.gnaba@email.com', 'Claire', TRUE, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('alex.konan@email.com', 'Alex', TRUE, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('nina.cisse@email.com', 'Nina', TRUE, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('eric.yapi@email.com', 'Eric', TRUE, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- =====================================================
-- INSERTION: Messages de contact
-- =====================================================
INSERT INTO messages (name, email, phone, subject, message, is_read, created_at) VALUES
('Awa Sylla', 'awa.sylla@email.com', '+225 07 11 22 33 44', 'Demande d\'information', 'Bonjour, je souhaiterais savoir si vous proposez des échantillons avant l\'achat d\'un parfum. Merci.', TRUE, DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Mamadou Diarra', 'mamadou.d@email.com', '+225 05 55 66 77 88', 'Livraison', 'Quels sont vos délais de livraison pour Bouaké ? Et les frais associés ?', TRUE, DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Christelle Koné', 'christelle.k@email.com', NULL, 'Partenariat', 'Je suis influenceuse beauté et j\'aimerais discuter d\'un éventuel partenariat avec votre marque.', FALSE, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('Franck Bamba', 'franck.b@email.com', '+225 01 99 88 77 66', 'Commande groupée', 'Proposez-vous des tarifs préférentiels pour les commandes groupées ? Nous sommes une entreprise et souhaitons offrir des parfums à nos collaborateurs.', FALSE, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- =====================================================
-- INSERTION: Notifications
-- =====================================================
INSERT INTO notifications (user_id, type, title, message, link, is_read, created_at) VALUES
(1, 'order', 'Commande livrée', 'Votre commande TV-2024-000001 a été livrée avec succès.', '/compte.php?tab=orders', TRUE, DATE_SUB(NOW(), INTERVAL 28 DAY)),
(2, 'order', 'Commande expédiée', 'Votre commande TV-2024-000002 a été expédiée.', '/compte.php?tab=orders', TRUE, DATE_SUB(NOW(), INTERVAL 24 DAY)),
(1, 'promo', 'Offre exclusive !', 'Profitez de -20% sur votre prochain achat avec le code LUXE20', '/boutique.php', FALSE, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 'order', 'Commande en cours', 'Votre commande TV-2024-000004 est en cours de préparation.', '/compte.php?tab=orders', FALSE, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(5, 'order', 'Commande confirmée', 'Votre commande TV-2024-000005 a été confirmée.', '/compte.php?tab=orders', FALSE, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- =====================================================
-- INSERTION: Paramètres du site
-- =====================================================
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'TATAVERNIS', 'general'),
('site_tagline', 'Maison de Parfumerie Premium', 'general'),
('site_email', 'contact@tatavernis.ci', 'general'),
('site_phone', '+225 07 00 00 00 00', 'general'),
('site_address', 'Cocody Riviera Bonoumin, Abidjan, Côte d\'Ivoire', 'general'),
('site_currency', 'FCFA', 'general'),
('shipping_standard_price', '3000', 'shipping'),
('shipping_express_price', '5000', 'shipping'),
('shipping_free_threshold', '75000', 'shipping'),
('tax_rate', '18', 'tax'),
('loyalty_points_rate', '100', 'loyalty'),
('facebook_url', 'https://facebook.com/tatavernis', 'social'),
('instagram_url', 'https://instagram.com/tatavernis', 'social'),
('whatsapp_number', '+225 07 00 00 00 00', 'social');

-- =====================================================
-- FIN DU SCRIPT D'INSERTION
-- =====================================================
