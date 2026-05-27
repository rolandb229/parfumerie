<?php
/**
 * TATAVERNIS - API Wishlist
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Auth.php';
require_once '../classes/Database.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$auth = Auth::getInstance();

if (!$auth->isLoggedIn()) {
    Security::jsonResponse(['success' => false, 'message' => 'Connexion requise', 'redirect' => '/connexion.php']);
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)($input['product_id'] ?? 0);

if ($productId <= 0) {
    Security::jsonResponse(['success' => false, 'message' => 'ID produit invalide']);
}

$db = Database::getInstance();
$userId = $auth->userId();

// Verifier si deja dans les favoris
$existing = $db->fetch(
    "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?",
    [$userId, $productId]
);

if ($existing) {
    // Retirer des favoris
    $db->delete('wishlist', 'id = ?', [$existing['id']]);
    Security::jsonResponse([
        'success' => true,
        'action' => 'removed',
        'message' => 'Retiré des favoris'
    ]);
} else {
    // Ajouter aux favoris
    $db->insert('wishlist', [
        'user_id' => $userId,
        'product_id' => $productId
    ]);
    Security::jsonResponse([
        'success' => true,
        'action' => 'added',
        'message' => 'Ajouté aux favoris'
    ]);
}
