<?php
/**
 * Configuration globale du site
 * TATAVERNIS - Maison de Parfumerie Premium
 */

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Informations du site
define('SITE_NAME', 'TATAVERNIS');
define('SITE_TAGLINE', "L'Art de Sublimer Chaque Essence");
define('SITE_DESCRIPTION', 'Des créations uniques, inspirées de l\'élégance et de la magie des senteurs.');
define('SITE_EMAIL', 'contact@tatavernis.com');
define('SITE_PHONE', '0187999990');
define('SITE_WHATSAPP', '+2250187999990');
define('SITE_ADDRESS', 'Abidjan, Côte d\'Ivoire');

// URLs du site
define('SITE_URL', 'http://localhost/parfumerie');
define('ADMIN_URL', SITE_URL . '/admin');
define('API_URL', SITE_URL . '/api');
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Chemins physiques
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CLASSES_PATH', ROOT_PATH . '/classes');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('TEMPLATES_PATH', ROOT_PATH . '/templates');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('API_PATH', ROOT_PATH . '/api');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Configuration uploads
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('MAX_VIDEO_SIZE', 100 * 1024 * 1024); // 100MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/ogg']);

// Configuration panier
define('CART_SESSION_NAME', 'tatavernis_cart');
define('WISHLIST_SESSION_NAME', 'tatavernis_wishlist');

// Devise
define('CURRENCY', 'FCFA');
define('CURRENCY_SYMBOL', 'FCFA');

// Livraison gratuite à partir de
define('FREE_SHIPPING_THRESHOLD', 50000);
define('SHIPPING_COST', 3000);

// Pagination
define('PRODUCTS_PER_PAGE', 12);
define('BLOG_POSTS_PER_PAGE', 9);
define('ORDERS_PER_PAGE', 10);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Réseaux sociaux
define('SOCIAL_FACEBOOK', 'https://facebook.com/tatavernis');
define('SOCIAL_INSTAGRAM', 'https://instagram.com/tatavernis');
define('SOCIAL_TIKTOK', 'https://tiktok.com/@tatavernis');
define('SOCIAL_YOUTUBE', 'https://youtube.com/@tatavernis');

// Mode debug (désactiver en production)
define('DEBUG_MODE', true);

// Gestion des erreurs
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Africa/Abidjan');

// Inclure les fichiers nécessaires
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/constants.php';
require_once CLASSES_PATH . '/Security.php';
require_once INCLUDES_PATH . '/functions.php';
