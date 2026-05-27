<?php
/**
 * Classe Cart - Gestion du panier
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Product.php';

class Cart
{
    private Database $db;
    private Product $product;
    private ?int $userId;
    private string $sessionId;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->product = new Product();
        $this->userId = $_SESSION['auth']['id'] ?? null;
        $this->sessionId = session_id();
    }

    /**
     * Obtenir le contenu du panier
     */
    public function getItems(): array
    {
        if ($this->userId) {
            $sql = "SELECT c.*, p.name, p.slug, p.price, p.compare_price, p.main_image, p.stock_quantity
                    FROM cart c
                    JOIN products p ON c.product_id = p.id
                    WHERE c.user_id = ?
                    ORDER BY c.created_at DESC";
            return $this->db->fetchAll($sql, [$this->userId]);
        } else {
            $sql = "SELECT c.*, p.name, p.slug, p.price, p.compare_price, p.main_image, p.stock_quantity
                    FROM cart c
                    JOIN products p ON c.product_id = p.id
                    WHERE c.session_id = ?
                    ORDER BY c.created_at DESC";
            return $this->db->fetchAll($sql, [$this->sessionId]);
        }
    }

    /**
     * Ajouter un produit au panier
     */
    public function add(int $productId, int $quantity = 1, ?string $size = null): array
    {
        // Vérifier que le produit existe et est disponible
        $product = $this->product->getById($productId);
        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => "Ce produit n'est pas disponible."];
        }

        // Vérifier le stock
        if ($product['stock_quantity'] < $quantity) {
            return ['success' => false, 'message' => "Stock insuffisant. Disponible: {$product['stock_quantity']}"];
        }

        // Vérifier si le produit est déjà dans le panier
        $existing = $this->getCartItem($productId, $size);

        if ($existing) {
            // Mettre à jour la quantité
            $newQuantity = $existing['quantity'] + $quantity;
            if ($newQuantity > $product['stock_quantity']) {
                $newQuantity = $product['stock_quantity'];
            }
            return $this->updateQuantity($existing['id'], $newQuantity);
        } else {
            // Ajouter au panier
            $data = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size
            ];

            if ($this->userId) {
                $data['user_id'] = $this->userId;
            } else {
                $data['session_id'] = $this->sessionId;
            }

            $this->db->insert('cart', $data);
            return [
                'success' => true,
                'message' => "Produit ajouté au panier.",
                'cart_count' => $this->getCount()
            ];
        }
    }

    /**
     * Obtenir un article du panier
     */
    private function getCartItem(int $productId, ?string $size = null): ?array
    {
        $where = $this->userId ? "user_id = ?" : "session_id = ?";
        $params = [$this->userId ?? $this->sessionId, $productId];

        $sql = "SELECT * FROM cart WHERE {$where} AND product_id = ?";
        
        if ($size) {
            $sql .= " AND size = ?";
            $params[] = $size;
        } else {
            $sql .= " AND size IS NULL";
        }

        return $this->db->fetch($sql, $params);
    }

    /**
     * Mettre à jour la quantité d'un article
     */
    public function updateQuantity(int $cartId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->remove($cartId);
        }

        // Vérifier le stock
        $item = $this->db->fetch(
            "SELECT c.*, p.stock_quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ?",
            [$cartId]
        );

        if (!$item) {
            return ['success' => false, 'message' => "Article non trouvé."];
        }

        if ($quantity > $item['stock_quantity']) {
            return ['success' => false, 'message' => "Stock insuffisant. Maximum: {$item['stock_quantity']}"];
        }

        $this->db->update('cart', ['quantity' => $quantity], 'id = ?', [$cartId]);

        return [
            'success' => true,
            'message' => "Quantité mise à jour.",
            'totals' => $this->getTotals()
        ];
    }

    /**
     * Supprimer un article du panier
     */
    public function remove(int $cartId): array
    {
        $where = $this->userId ? "id = ? AND user_id = ?" : "id = ? AND session_id = ?";
        $params = [$cartId, $this->userId ?? $this->sessionId];

        $this->db->query("DELETE FROM cart WHERE {$where}", $params);

        return [
            'success' => true,
            'message' => "Article supprimé du panier.",
            'cart_count' => $this->getCount(),
            'totals' => $this->getTotals()
        ];
    }

    /**
     * Vider le panier
     */
    public function clear(): void
    {
        if ($this->userId) {
            $this->db->delete('cart', 'user_id = ?', [$this->userId]);
        } else {
            $this->db->delete('cart', 'session_id = ?', [$this->sessionId]);
        }
    }

    /**
     * Obtenir le nombre d'articles dans le panier
     */
    public function getCount(): int
    {
        if ($this->userId) {
            $result = $this->db->fetch(
                "SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE user_id = ?",
                [$this->userId]
            );
        } else {
            $result = $this->db->fetch(
                "SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE session_id = ?",
                [$this->sessionId]
            );
        }

        return (int) $result['count'];
    }

    /**
     * Calculer les totaux du panier
     */
    public function getTotals(): array
    {
        $items = $this->getItems();
        
        $subtotal = 0;
        $itemsCount = 0;

        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $itemsCount += $item['quantity'];
        }

        // Calculer la livraison
        $shipping = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;

        // Calculer la réduction (code promo)
        $discount = $_SESSION['cart_discount'] ?? 0;

        // Total
        $total = $subtotal + $shipping - $discount;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => max(0, $total),
            'items_count' => $itemsCount,
            'free_shipping_remaining' => max(0, FREE_SHIPPING_THRESHOLD - $subtotal)
        ];
    }

    /**
     * Appliquer un code promo
     */
    public function applyCoupon(string $code): array
    {
        $coupon = $this->db->fetch(
            "SELECT * FROM coupons WHERE code = ? AND is_active = 1 
             AND (starts_at IS NULL OR starts_at <= NOW())
             AND (expires_at IS NULL OR expires_at >= NOW())
             AND (usage_limit IS NULL OR usage_count < usage_limit)",
            [strtoupper($code)]
        );

        if (!$coupon) {
            return ['success' => false, 'message' => "Code promo invalide ou expiré."];
        }

        $totals = $this->getTotals();

        // Vérifier le montant minimum
        if ($coupon['min_order_amount'] && $totals['subtotal'] < $coupon['min_order_amount']) {
            return [
                'success' => false,
                'message' => "Commande minimum de " . number_format($coupon['min_order_amount'], 0, ',', ' ') . " FCFA requise."
            ];
        }

        // Calculer la réduction
        $discount = 0;
        switch ($coupon['type']) {
            case 'percentage':
                $discount = $totals['subtotal'] * ($coupon['value'] / 100);
                if ($coupon['max_discount_amount']) {
                    $discount = min($discount, $coupon['max_discount_amount']);
                }
                break;
            case 'fixed':
                $discount = $coupon['value'];
                break;
            case 'free_shipping':
                $_SESSION['free_shipping'] = true;
                break;
        }

        $_SESSION['cart_discount'] = $discount;
        $_SESSION['cart_coupon'] = $coupon['code'];

        return [
            'success' => true,
            'message' => "Code promo appliqué!",
            'discount' => $discount,
            'totals' => $this->getTotals()
        ];
    }

    /**
     * Supprimer le code promo
     */
    public function removeCoupon(): void
    {
        unset($_SESSION['cart_discount'], $_SESSION['cart_coupon'], $_SESSION['free_shipping']);
    }

    /**
     * Fusionner le panier session avec le panier utilisateur
     */
    public function mergeSessionCart(int $userId): void
    {
        $sessionId = session_id();
        
        // Récupérer les articles de la session
        $sessionItems = $this->db->fetchAll(
            "SELECT * FROM cart WHERE session_id = ?",
            [$sessionId]
        );

        foreach ($sessionItems as $item) {
            // Vérifier si le produit existe déjà dans le panier utilisateur
            $existing = $this->db->fetch(
                "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND (size = ? OR (size IS NULL AND ? IS NULL))",
                [$userId, $item['product_id'], $item['size'], $item['size']]
            );

            if ($existing) {
                // Mettre à jour la quantité
                $this->db->update(
                    'cart',
                    ['quantity' => $existing['quantity'] + $item['quantity']],
                    'id = ?',
                    [$existing['id']]
                );
            } else {
                // Transférer l'article
                $this->db->update(
                    'cart',
                    ['user_id' => $userId, 'session_id' => null],
                    'id = ?',
                    [$item['id']]
                );
            }
        }

        // Supprimer les doublons restants
        $this->db->query(
            "DELETE FROM cart WHERE session_id = ?",
            [$sessionId]
        );
    }
}
