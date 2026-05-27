<?php
/**
 * TATAVERNIS - API Panier
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/config.php';
require_once '../classes/Cart.php';
require_once '../classes/Product.php';
require_once '../classes/Security.php';

$security = new Security();
$cart = new Cart();
$product = new Product();

// Récupérer les données
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'add':
            $productId = (int)($input['product_id'] ?? 0);
            $quantity = (int)($input['quantity'] ?? 1);
            $variantId = (int)($input['variant_id'] ?? 0);
            
            if ($productId <= 0) {
                throw new Exception('ID produit invalide');
            }
            
            // Vérifier le stock
            $prod = $product->getById($productId);
            if (!$prod) {
                throw new Exception('Produit introuvable');
            }
            
            if (($prod['stock'] ?? 0) < $quantity) {
                throw new Exception('Stock insuffisant');
            }
            
            $cart->add($productId, $quantity, $variantId);
            
            $response = [
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cart_count' => $cart->getCount(),
                'cart_total' => $cart->getTotal()
            ];
            break;
            
        case 'update':
            $itemId = (int)($input['item_id'] ?? 0);
            $quantity = (int)($input['quantity'] ?? 1);
            
            if ($itemId <= 0 || $quantity < 0) {
                throw new Exception('Paramètres invalides');
            }
            
            if ($quantity === 0) {
                $cart->remove($itemId);
            } else {
                $cart->update($itemId, $quantity);
            }
            
            $response = [
                'success' => true,
                'message' => 'Panier mis à jour',
                'cart_count' => $cart->getCount(),
                'cart_total' => $cart->getTotal(),
                'items' => $cart->getItems()
            ];
            break;
            
        case 'remove':
            $itemId = (int)($input['item_id'] ?? 0);
            
            if ($itemId <= 0) {
                throw new Exception('ID article invalide');
            }
            
            $cart->remove($itemId);
            
            $response = [
                'success' => true,
                'message' => 'Article supprimé',
                'cart_count' => $cart->getCount(),
                'cart_total' => $cart->getTotal()
            ];
            break;
            
        case 'clear':
            $cart->clear();
            
            $response = [
                'success' => true,
                'message' => 'Panier vidé',
                'cart_count' => 0,
                'cart_total' => 0
            ];
            break;
            
        case 'get':
            $response = [
                'success' => true,
                'cart_count' => $cart->getCount(),
                'cart_total' => $cart->getTotal(),
                'items' => $cart->getItems()
            ];
            break;
            
        case 'apply_promo':
            $code = $security->sanitize($input['code'] ?? '');
            
            if (empty($code)) {
                throw new Exception('Code promo requis');
            }
            
            // Vérifier le code promo
            $promo = $cart->applyPromoCode($code);
            
            if ($promo) {
                $response = [
                    'success' => true,
                    'message' => 'Code promo appliqué',
                    'discount' => $promo['discount'],
                    'discount_type' => $promo['type'],
                    'cart_total' => $cart->getTotal()
                ];
            } else {
                throw new Exception('Code promo invalide ou expiré');
            }
            break;
            
        case 'remove_promo':
            $cart->removePromoCode();
            
            $response = [
                'success' => true,
                'message' => 'Code promo retiré',
                'cart_total' => $cart->getTotal()
            ];
            break;
            
        default:
            throw new Exception('Action non reconnue');
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
