-- =====================================================
-- TATAVERNIS - Schéma de Base de Données MySQL
-- Maison de Parfumerie Premium
-- =====================================================

-- Supprimer la base si elle existe et la recréer
DROP DATABASE IF EXISTS tatavernis;
CREATE DATABASE tatavernis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tatavernis;

-- =====================================================
-- TABLE: users (Clients)
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    gender ENUM('homme', 'femme', 'autre') DEFAULT NULL,
    birth_date DATE DEFAULT NULL,
    newsletter BOOLEAN DEFAULT FALSE,
    email_verified BOOLEAN DEFAULT FALSE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expires TIMESTAMP NULL,
    loyalty_points INT DEFAULT 0,
    total_spent DECIMAL(12, 2) DEFAULT 0.00,
    orders_count INT DEFAULT 0,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: admins (Administrateurs)
-- =====================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('admin', 'super_admin', 'editor') DEFAULT 'admin',
    permissions JSON DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: categories
-- =====================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: products
-- =====================================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT,
    description LONGTEXT,
    category_id INT,
    brand VARCHAR(100) DEFAULT 'TATAVERNIS',
    
    -- Prix
    price DECIMAL(10, 2) NOT NULL,
    compare_price DECIMAL(10, 2) DEFAULT NULL,
    cost_price DECIMAL(10, 2) DEFAULT NULL,
    
    -- Stock
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 5,
    track_inventory BOOLEAN DEFAULT TRUE,
    
    -- Caractéristiques parfum
    genre ENUM('boisé', 'floral', 'oriental', 'frais', 'épicé', 'ambré', 'musqué', 'fruité', 'gourmand', 'aquatique') DEFAULT NULL,
    target ENUM('homme', 'femme', 'unisexe') DEFAULT NULL,
    concentration ENUM('extrait', 'edp', 'edt', 'edc', 'brume') DEFAULT 'edp',
    
    -- Notes olfactives (pyramide)
    notes_top TEXT,
    notes_heart TEXT,
    notes_base TEXT,
    
    -- Contenances disponibles (JSON)
    sizes JSON DEFAULT NULL,
    
    -- Médias
    main_image VARCHAR(255) DEFAULT NULL,
    video_url VARCHAR(255) DEFAULT NULL,
    
    -- Statuts
    is_featured BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT TRUE,
    is_bestseller BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    
    -- SEO
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    
    -- Statistiques
    views_count INT DEFAULT 0,
    sales_count INT DEFAULT 0,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_sku (sku),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_price (price)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: product_images
-- =====================================================
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: product_videos
-- =====================================================
CREATE TABLE product_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    video_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    title VARCHAR(255) DEFAULT NULL,
    duration INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: tags
-- =====================================================
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: product_tags (Relation many-to-many)
-- =====================================================
CREATE TABLE product_tags (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: addresses (Adresses utilisateurs)
-- =====================================================
CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('billing', 'shipping') DEFAULT 'shipping',
    is_default BOOLEAN DEFAULT FALSE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255) DEFAULT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) DEFAULT NULL,
    postal_code VARCHAR(20) DEFAULT NULL,
    country VARCHAR(100) DEFAULT 'Côte d\'Ivoire',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: orders (Commandes)
-- =====================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT,
    
    -- Montants
    subtotal DECIMAL(12, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0.00,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    tax_amount DECIMAL(10, 2) DEFAULT 0.00,
    total DECIMAL(12, 2) NOT NULL,
    
    -- Statuts
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method ENUM('cash', 'mobile_money', 'card', 'transfer') DEFAULT 'cash',
    
    -- Livraison
    shipping_method ENUM('standard', 'express', 'pickup') DEFAULT 'standard',
    tracking_number VARCHAR(100) DEFAULT NULL,
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    
    -- Adresse de livraison (copie pour historique)
    shipping_first_name VARCHAR(100),
    shipping_last_name VARCHAR(100),
    shipping_phone VARCHAR(20),
    shipping_address VARCHAR(255),
    shipping_city VARCHAR(100),
    shipping_country VARCHAR(100) DEFAULT 'Côte d\'Ivoire',
    
    -- Code promo utilisé
    coupon_code VARCHAR(50) DEFAULT NULL,
    
    -- Notes
    customer_notes TEXT DEFAULT NULL,
    admin_notes TEXT DEFAULT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: order_items (Articles de commande)
-- =====================================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(50),
    product_image VARCHAR(255),
    size VARCHAR(20) DEFAULT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_order (order_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: cart (Panier persistant)
-- =====================================================
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    session_id VARCHAR(255) DEFAULT NULL,
    product_id INT NOT NULL,
    size VARCHAR(20) DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: wishlist (Liste de souhaits)
-- =====================================================
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: reviews (Avis clients)
-- =====================================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT DEFAULT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255) DEFAULT NULL,
    comment TEXT,
    pros TEXT DEFAULT NULL,
    cons TEXT DEFAULT NULL,
    is_verified_purchase BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    helpful_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: coupons (Codes promo)
-- =====================================================
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('percentage', 'fixed', 'free_shipping') DEFAULT 'percentage',
    value DECIMAL(10, 2) NOT NULL,
    min_order_amount DECIMAL(10, 2) DEFAULT NULL,
    max_discount_amount DECIMAL(10, 2) DEFAULT NULL,
    usage_limit INT DEFAULT NULL,
    usage_count INT DEFAULT 0,
    usage_per_user INT DEFAULT 1,
    starts_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: promotions (Promotions/Flash Sales)
-- =====================================================
CREATE TABLE promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    banner_image VARCHAR(255) DEFAULT NULL,
    banner_mobile VARCHAR(255) DEFAULT NULL,
    discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    discount_value DECIMAL(10, 2) NOT NULL,
    starts_at TIMESTAMP NOT NULL,
    ends_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_dates (starts_at, ends_at)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: promotion_products (Produits en promo)
-- =====================================================
CREATE TABLE promotion_products (
    promotion_id INT NOT NULL,
    product_id INT NOT NULL,
    PRIMARY KEY (promotion_id, product_id),
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: blog_posts (Articles de blog)
-- =====================================================
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT,
    featured_image VARCHAR(255) DEFAULT NULL,
    featured_video VARCHAR(255) DEFAULT NULL,
    author_id INT,
    category VARCHAR(100) DEFAULT NULL,
    tags JSON DEFAULT NULL,
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    reading_time INT DEFAULT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    scheduled_at TIMESTAMP NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published (published_at)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: blog_media (Médias des articles)
-- =====================================================
CREATE TABLE blog_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    type ENUM('image', 'video', 'audio') DEFAULT 'image',
    file_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    title VARCHAR(255) DEFAULT NULL,
    caption TEXT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    INDEX idx_post (post_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: blog_comments (Commentaires de blog)
-- =====================================================
CREATE TABLE blog_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    author_name VARCHAR(100),
    author_email VARCHAR(255),
    content TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES blog_comments(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: notifications
-- =====================================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    admin_id INT DEFAULT NULL,
    type ENUM('order', 'promo', 'stock', 'message', 'system') DEFAULT 'system',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_admin (admin_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: messages (Messages de contact)
-- =====================================================
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_read (is_read)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: newsletter_subscribers
-- =====================================================
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(100) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: settings (Configuration globale)
-- =====================================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json', 'html') DEFAULT 'text',
    description VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: activity_logs (Journal d'activité admin)
-- =====================================================
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT DEFAULT NULL,
    old_values JSON DEFAULT NULL,
    new_values JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_admin (admin_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- INSERTION DES DONNÉES INITIALES
-- =====================================================

-- Admin par défaut
INSERT INTO admins (username, email, password, first_name, last_name, role) VALUES
('admin', 'admin@tatavernis.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'TATAVERNIS', 'super_admin');
-- Mot de passe par défaut: password

-- Catégories principales
INSERT INTO categories (name, slug, description, sort_order, is_featured) VALUES
('Parfums Homme', 'parfums-homme', 'Élégance & Caractère - Des fragrances masculines raffinées', 1, TRUE),
('Parfums Femme', 'parfums-femme', 'Douceur & Séduction - Des senteurs féminines envoûtantes', 2, TRUE),
('Senteurs Unisexes', 'senteurs-unisexes', 'Équilibre & Harmonie - Des parfums pour tous', 3, TRUE),
('Senteurs Maison', 'senteurs-maison', 'Ambiance & Bien-être - Parfumez votre intérieur', 4, TRUE),
('Coffrets Prestige', 'coffrets-prestige', 'Luxe & Raffinement - Nos coffrets cadeaux exclusifs', 5, TRUE);

-- Tags populaires
INSERT INTO tags (name, slug) VALUES
('Nouveau', 'nouveau'),
('Bestseller', 'bestseller'),
('Exclusif', 'exclusif'),
('Édition Limitée', 'edition-limitee'),
('Promo', 'promo'),
('Été', 'ete'),
('Hiver', 'hiver'),
('Soirée', 'soiree'),
('Quotidien', 'quotidien');

-- Paramètres par défaut
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'TATAVERNIS', 'text', 'Nom du site'),
('site_tagline', 'L''Art de Sublimer Chaque Essence', 'text', 'Slogan du site'),
('site_email', 'contact@tatavernis.com', 'text', 'Email de contact'),
('site_phone', '0187999990', 'text', 'Téléphone'),
('site_address', 'Abidjan, Côte d''Ivoire', 'text', 'Adresse'),
('currency', 'FCFA', 'text', 'Devise'),
('free_shipping_threshold', '50000', 'number', 'Seuil livraison gratuite'),
('shipping_cost', '3000', 'number', 'Frais de livraison standard'),
('maintenance_mode', '0', 'boolean', 'Mode maintenance'),
('allow_guest_checkout', '1', 'boolean', 'Autoriser achat sans compte');

-- =====================================================
-- PRODUITS DE DÉMONSTRATION
-- =====================================================

INSERT INTO products (sku, name, slug, short_description, description, category_id, price, compare_price, stock_quantity, genre, target, concentration, notes_top, notes_heart, notes_base, sizes, main_image, is_featured, is_new, is_bestseller) VALUES
('TV-OUD-001', 'Oud Royal', 'oud-royal', 'Un parfum oriental boisé, intense et mystérieux.', 'Oud Royal incarne la puissance et le raffinement. Cette fragrance d''exception marie les notes précieuses de l''oud aux accords épicés et ambrés pour une signature olfactive inoubliable. Un parfum qui laisse une empreinte indélébile.', 1, 120000, 150000, 50, 'oriental', 'homme', 'extrait', 'Safran, Bergamote', 'Oud, Rose, Patchouli', 'Ambre, Musc, Vanille', '["50ml", "100ml", "150ml"]', 'products/oud-royal.jpg', TRUE, FALSE, TRUE),

('TV-VAN-002', 'Vanille Noire', 'vanille-noire', 'Une vanille gourmande et sensuelle.', 'Vanille Noire révèle une facette inattendue de la vanille, sublimée par des notes de fève tonka et de bois précieux. Un parfum enveloppant qui éveille les sens et réchauffe l''âme.', 2, 80000, NULL, 75, 'gourmand', 'femme', 'edp', 'Fleur d''oranger, Mandarine', 'Vanille de Madagascar, Fève Tonka', 'Santal, Musc blanc, Caramel', '["30ml", "50ml", "100ml"]', 'products/vanille-noire.jpg', TRUE, FALSE, TRUE),

('TV-AMB-003', 'Ambre Éternel', 'ambre-eternel', 'Un ambre chaleureux et envoûtant.', 'Ambre Éternel capture l''essence même de la sensualité orientale. Cette composition riche et complexe associe l''ambre précieux à des notes épicées et résineuses pour un sillage captivant.', 3, 85000, NULL, 60, 'ambré', 'unisexe', 'edp', 'Encens, Cardamome', 'Ambre gris, Benjoin', 'Oud, Labdanum, Vanille', '["50ml", "100ml"]', 'products/ambre-eternel.jpg', TRUE, TRUE, FALSE),

('TV-VEL-004', 'Velvet Rose', 'velvet-rose', 'Une rose veloutée et romantique.', 'Velvet Rose célèbre la reine des fleurs dans toute sa splendeur. Une rose de Damas aux pétales de velours, magnifiée par des notes de pivoine et de musc pour une féminité absolue.', 2, 85000, NULL, 45, 'floral', 'femme', 'edp', 'Pivoine, Bergamote, Litchi', 'Rose de Damas, Jasmin', 'Musc, Bois de cèdre, Patchouli', '["30ml", "50ml", "100ml"]', 'products/velvet-rose.jpg', FALSE, TRUE, FALSE),

('TV-CIT-005', 'Citrus Gold', 'citrus-gold', 'Une fraîcheur dorée et pétillante.', 'Citrus Gold capture l''éclat du soleil méditerranéen. Un cocktail d''agrumes nobles rehaussé de notes aromatiques pour une fraîcheur sophistiquée et lumineuse.', 1, 75000, NULL, 80, 'frais', 'homme', 'edt', 'Citron de Sicile, Bergamote, Orange', 'Néroli, Romarin', 'Vétiver, Cèdre, Musc blanc', '["50ml", "100ml", "150ml"]', 'products/citrus-gold.jpg', FALSE, TRUE, FALSE),

('TV-BOI-006', 'Bois Précieux', 'bois-precieux', 'Un boisé noble et élégant.', 'Bois Précieux est un hommage aux essences les plus nobles. Le santal crémeux s''unit au cèdre majestueux et au vétiver profond pour une élégance masculine intemporelle.', 1, 90000, NULL, 55, 'boisé', 'homme', 'edp', 'Poivre noir, Gingembre', 'Santal, Cèdre du Liban', 'Vétiver, Cuir, Ambre', '["50ml", "100ml"]', 'products/bois-precieux.jpg', TRUE, FALSE, TRUE),

('TV-MUS-007', 'Musc Blanc', 'musc-blanc', 'Un musc pur et aérien.', 'Musc Blanc est une ode à la pureté. Ce parfum délicat et enveloppant évoque la douceur d''une seconde peau, sublimé par des notes poudrées et florales.', 2, 70000, NULL, 90, 'musqué', 'femme', 'edp', 'Aldéhydes, Poire', 'Musc blanc, Iris', 'Bois de santal, Ambrette', '["30ml", "50ml", "100ml"]', 'products/musc-blanc.jpg', FALSE, FALSE, TRUE),

('TV-LUM-008', 'Lumière Blanche', 'lumiere-blanche', 'Une luminosité florale délicate.', 'Lumière Blanche capture l''essence d''un jardin baigné de lumière. Les fleurs blanches les plus précieuses s''entrelacent dans une composition aérienne et raffinée.', 2, 100000, 120000, 35, 'floral', 'femme', 'edp', 'Gardénia, Néroli', 'Jasmin Sambac, Tubéreuse, Lys', 'Musc, Santal, Cèdre blanc', '["50ml", "100ml"]', 'products/lumiere-blanche.jpg', TRUE, TRUE, FALSE);

-- =====================================================
-- FIN DU SCHÉMA
-- =====================================================
