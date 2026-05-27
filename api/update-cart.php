<?php
/**
 * TATAVERNIS - API Mise à jour panier
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Cart.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$cartId = (int)($input['cart_id'] ?? 0);
$quantity = (int)($input['quantity'] ?? 1);

if ($cartId <= 0) {
    Security::jsonResponse(['success' => false, 'message' => 'ID article invalide']);
}

$cart = new Cart();
$result = $cart->updateQuantity($cartId, $quantity);

Security::jsonResponse($result);
