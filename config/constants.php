<?php
/**
 * Constantes du projet
 * TATAVERNIS - Maison de Parfumerie Premium
 */

// Statuts des commandes
define('ORDER_STATUS', [
    'pending' => 'En attente',
    'confirmed' => 'Confirmée',
    'processing' => 'En préparation',
    'shipped' => 'Expédiée',
    'delivered' => 'Livrée',
    'cancelled' => 'Annulée',
    'refunded' => 'Remboursée'
]);

// Statuts des paiements
define('PAYMENT_STATUS', [
    'pending' => 'En attente',
    'paid' => 'Payé',
    'failed' => 'Échoué',
    'refunded' => 'Remboursé'
]);

// Méthodes de paiement
define('PAYMENT_METHODS', [
    'cash' => 'Paiement à la livraison',
    'mobile_money' => 'Mobile Money',
    'card' => 'Carte bancaire',
    'transfer' => 'Virement bancaire'
]);

// Méthodes de livraison
define('SHIPPING_METHODS', [
    'standard' => [
        'name' => 'Livraison standard',
        'delay' => '3-5 jours',
        'cost' => 3000
    ],
    'express' => [
        'name' => 'Livraison express',
        'delay' => '24-48h',
        'cost' => 5000
    ],
    'pickup' => [
        'name' => 'Retrait en boutique',
        'delay' => 'Immédiat',
        'cost' => 0
    ]
]);

// Catégories de parfums
define('PERFUME_CATEGORIES', [
    'homme' => 'Parfums Homme',
    'femme' => 'Parfums Femme',
    'unisexe' => 'Senteurs Unisexes',
    'maison' => 'Senteurs Maison',
    'coffret' => 'Coffrets Prestige'
]);

// Genres de parfums
define('PERFUME_GENRES', [
    'boisé' => 'Boisé',
    'floral' => 'Floral',
    'oriental' => 'Oriental',
    'frais' => 'Frais',
    'épicé' => 'Épicé',
    'ambré' => 'Ambré',
    'musqué' => 'Musqué',
    'fruité' => 'Fruité',
    'gourmand' => 'Gourmand',
    'aquatique' => 'Aquatique'
]);

// Concentrations de parfums
define('PERFUME_CONCENTRATIONS', [
    'extrait' => 'Extrait de Parfum',
    'edp' => 'Eau de Parfum',
    'edt' => 'Eau de Toilette',
    'edc' => 'Eau de Cologne',
    'brume' => 'Brume parfumée'
]);

// Contenances disponibles
define('PERFUME_SIZES', [
    '30' => '30 ml',
    '50' => '50 ml',
    '100' => '100 ml',
    '150' => '150 ml',
    '200' => '200 ml'
]);

// Types de promotions
define('PROMO_TYPES', [
    'percentage' => 'Pourcentage',
    'fixed' => 'Montant fixe',
    'free_shipping' => 'Livraison gratuite',
    'buy_x_get_y' => 'Achetez X, obtenez Y'
]);

// Rôles utilisateurs
define('USER_ROLES', [
    'customer' => 'Client',
    'admin' => 'Administrateur',
    'super_admin' => 'Super Administrateur',
    'editor' => 'Éditeur'
]);

// Types de notifications
define('NOTIFICATION_TYPES', [
    'order' => 'Commande',
    'promo' => 'Promotion',
    'stock' => 'Stock',
    'message' => 'Message',
    'system' => 'Système'
]);

// Couleurs du thème
define('THEME_COLORS', [
    'primary' => '#C8A96B',      // Or élégant
    'secondary' => '#0B0B0B',    // Noir profond
    'accent' => '#F8F5F0',       // Blanc cassé
    'muted' => '#2C2C2C',        // Gris fumé
    'gold' => '#C8A96B',         // Or
    'gold_light' => '#D4B87A',   // Or clair
    'gold_dark' => '#A8894B'     // Or foncé
]);
