<?php
/**
 * TATAVERNIS - API Recherche
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Product.php';
require_once '../classes/Security.php';

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 2) {
    Security::jsonResponse(['success' => true, 'products' => []]);
}

$product = new Product();
$products = $product->search($query, 10);

Security::jsonResponse([
    'success' => true,
    'products' => $products
]);
