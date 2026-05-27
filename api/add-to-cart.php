<?php
/**
 * TATAVERNIS - API Ajout au panier
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Cart.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$productId = (int)($input['product_id'] ?? 0);
$quantity = (int)($input['quantity'] ?? 1);
$size = $input['size'] ?? null;

if ($productId <= 0) {
    Security::jsonResponse(['success' => false, 'message' => 'ID produit invalide']);
}

$cart = new Cart();
$result = $cart->add($productId, $quantity, $size);

Security::jsonResponse($result);
