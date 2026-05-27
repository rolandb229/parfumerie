<?php
/**
 * Classe Order - Gestion des commandes
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Cart.php';

class Order
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une commande
     */
    public function create(array $data, array $cartItems, array $totals): array
    {
        $this->db->beginTransaction();

        try {
            // Générer le numéro de commande
            $orderNumber = $this->generateOrderNumber();

            // Créer la commande
            $orderId = $this->db->insert('orders', [
                'order_number' => $orderNumber,
                'user_id' => $data['user_id'] ?? null,
                'subtotal' => $totals['subtotal'],
                'shipping_cost' => $totals['shipping'],
                'discount_amount' => $totals['discount'],
                'total' => $totals['total'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'shipping_method' => $data['shipping_method'] ?? 'standard',
                'shipping_first_name' => $data['first_name'],
                'shipping_last_name' => $data['last_name'],
                'shipping_phone' => $data['phone'],
                'shipping_address' => $data['address'],
                'shipping_city' => $data['city'],
                'shipping_country' => $data['country'] ?? 'Côte d\'Ivoire',
                'coupon_code' => $_SESSION['cart_coupon'] ?? null,
                'customer_notes' => $data['notes'] ?? null
            ]);

            // Ajouter les articles
            foreach ($cartItems as $item) {
                $this->db->insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? null,
                    'product_image' => $item['main_image'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity']
                ]);

                // Mettre à jour le stock
                $this->db->query(
                    "UPDATE products SET stock_quantity = stock_quantity - ?, sales_count = sales_count + ? WHERE id = ?",
                    [$item['quantity'], $item['quantity'], $item['product_id']]
                );
            }

            // Mettre à jour les statistiques utilisateur si connecté
            if (!empty($data['user_id'])) {
                $this->db->query(
                    "UPDATE users SET total_spent = total_spent + ?, orders_count = orders_count + 1 WHERE id = ?",
                    [$totals['total'], $data['user_id']]
                );
            }

            // Mettre à jour l'utilisation du coupon
            if (!empty($_SESSION['cart_coupon'])) {
                $this->db->query(
                    "UPDATE coupons SET usage_count = usage_count + 1 WHERE code = ?",
                    [$_SESSION['cart_coupon']]
                );
            }

            $this->db->commit();

            return [
                'success' => true,
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ];

        } catch (Exception $e) {
            $this->db->rollback();
            return [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création de la commande."
            ];
        }
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber(): string
    {
        $prefix = 'CMD';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Obtenir une commande par son ID
     */
    public function getById(int $id): ?array
    {
        $order = $this->db->fetch("SELECT * FROM orders WHERE id = ?", [$id]);
        
        if ($order) {
            $order['items'] = $this->getItems($id);
        }

        return $order;
    }

    /**
     * Obtenir une commande par son numéro
     */
    public function getByNumber(string $orderNumber): ?array
    {
        $order = $this->db->fetch("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
        
        if ($order) {
            $order['items'] = $this->getItems($order['id']);
        }

        return $order;
    }

    /**
     * Obtenir les articles d'une commande
     */
    public function getItems(int $orderId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?",
            [$orderId]
        );
    }

    /**
     * Obtenir les commandes d'un utilisateur
     */
    public function getByUser(int $userId, int $page = 1, int $perPage = ORDERS_PER_PAGE): array
    {
        $offset = ($page - 1) * $perPage;

        $total = $this->db->count('orders', 'user_id = ?', [$userId]);

        $orders = $this->db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            [$userId]
        );

        return [
            'orders' => $orders,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }

    /**
     * Obtenir toutes les commandes (Admin)
     */
    public function getAll(array $filters = [], int $page = 1, int $perPage = ADMIN_ITEMS_PER_PAGE): array
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['payment_status'])) {
            $where[] = "payment_status = ?";
            $params[] = $filters['payment_status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(order_number LIKE ? OR shipping_first_name LIKE ? OR shipping_last_name LIKE ? OR shipping_phone LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        if (!empty($filters['date_from'])) {
            $where[] = "DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = "DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $total = $this->db->count('orders', $whereClause, $params);

        $sql = "SELECT o.*, u.first_name as user_first_name, u.last_name as user_last_name, u.email as user_email
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE {$whereClause}
                ORDER BY o.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $orders = $this->db->fetchAll($sql, $params);

        return [
            'orders' => $orders,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $validStatuses = array_keys(ORDER_STATUS);
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $data = ['status' => $status];

        // Ajouter les timestamps selon le statut
        if ($status === 'shipped') {
            $data['shipped_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'delivered') {
            $data['delivered_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->update('orders', $data, 'id = ?', [$orderId]) > 0;
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaymentStatus(int $orderId, string $status): bool
    {
        $validStatuses = array_keys(PAYMENT_STATUS);
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->db->update('orders', ['payment_status' => $status], 'id = ?', [$orderId]) > 0;
    }

    /**
     * Ajouter un numéro de suivi
     */
    public function addTracking(int $orderId, string $trackingNumber): bool
    {
        return $this->db->update('orders', ['tracking_number' => $trackingNumber], 'id = ?', [$orderId]) > 0;
    }

    /**
     * Obtenir les statistiques des commandes (Admin)
     */
    public function getStats(): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total' => $this->db->count('orders'),
            'pending' => $this->db->count('orders', "status = 'pending'"),
            'processing' => $this->db->count('orders', "status IN ('confirmed', 'processing')"),
            'shipped' => $this->db->count('orders', "status = 'shipped'"),
            'delivered' => $this->db->count('orders', "status = 'delivered'"),
            'cancelled' => $this->db->count('orders', "status = 'cancelled'"),
            'today' => $this->db->count('orders', "DATE(created_at) = ?", [$today]),
            'this_month' => $this->db->count('orders', "DATE_FORMAT(created_at, '%Y-%m') = ?", [$thisMonth]),
            'revenue_today' => $this->getRevenue($today, $today),
            'revenue_month' => $this->getRevenue(date('Y-m-01'), $today)
        ];
    }

    /**
     * Obtenir le chiffre d'affaires sur une période
     */
    public function getRevenue(string $dateFrom, string $dateTo): float
    {
        $result = $this->db->fetch(
            "SELECT COALESCE(SUM(total), 0) as revenue FROM orders 
             WHERE DATE(created_at) BETWEEN ? AND ? 
             AND status NOT IN ('cancelled', 'refunded')
             AND payment_status = 'paid'",
            [$dateFrom, $dateTo]
        );

        return (float) $result['revenue'];
    }

    /**
     * Obtenir les commandes récentes (Admin dashboard)
     */
    public function getRecent(int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT o.*, u.first_name, u.last_name 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             ORDER BY o.created_at DESC 
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Obtenir les ventes par jour (pour graphique)
     */
    public function getSalesByDay(int $days = 7): array
    {
        $result = $this->db->fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue
             FROM orders
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             AND status NOT IN ('cancelled', 'refunded')
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            [$days]
        );

        return $result;
    }
}
