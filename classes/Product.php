<?php
/**
 * Classe Product - Gestion des produits
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';

class Product
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir tous les produits avec filtres
     */
    public function getAll(array $filters = [], int $page = 1, int $perPage = PRODUCTS_PER_PAGE): array
    {
        $where = ["p.status = 'active'"];
        $params = [];
        $orderBy = "p.created_at DESC";

        // Filtre par catégorie
        if (!empty($filters['category'])) {
            $where[] = "p.category_id = ?";
            $params[] = $filters['category'];
        }

        // Filtre par catégorie slug
        if (!empty($filters['category_slug'])) {
            $where[] = "c.slug = ?";
            $params[] = $filters['category_slug'];
        }

        // Filtre par genre (boisé, floral, etc.)
        if (!empty($filters['genre'])) {
            $where[] = "p.genre = ?";
            $params[] = $filters['genre'];
        }

        // Filtre par cible (homme, femme, unisexe)
        if (!empty($filters['target'])) {
            $where[] = "p.target = ?";
            $params[] = $filters['target'];
        }

        // Filtre par prix min
        if (!empty($filters['price_min'])) {
            $where[] = "p.price >= ?";
            $params[] = $filters['price_min'];
        }

        // Filtre par prix max
        if (!empty($filters['price_max'])) {
            $where[] = "p.price <= ?";
            $params[] = $filters['price_max'];
        }

        // Filtre featured
        if (!empty($filters['featured'])) {
            $where[] = "p.is_featured = 1";
        }

        // Filtre nouveautés
        if (!empty($filters['new'])) {
            $where[] = "p.is_new = 1";
        }

        // Filtre bestsellers
        if (!empty($filters['bestseller'])) {
            $where[] = "p.is_bestseller = 1";
        }

        // Filtre en stock
        if (!empty($filters['in_stock'])) {
            $where[] = "p.stock_quantity > 0";
        }

        // Recherche
        if (!empty($filters['search'])) {
            $where[] = "(p.name LIKE ? OR p.short_description LIKE ? OR p.sku LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Tri
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy = "p.price ASC";
                    break;
                case 'price_desc':
                    $orderBy = "p.price DESC";
                    break;
                case 'name_asc':
                    $orderBy = "p.name ASC";
                    break;
                case 'name_desc':
                    $orderBy = "p.name DESC";
                    break;
                case 'newest':
                    $orderBy = "p.created_at DESC";
                    break;
                case 'popular':
                    $orderBy = "p.sales_count DESC";
                    break;
                case 'rating':
                    $orderBy = "avg_rating DESC";
                    break;
            }
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        // Compter le total
        $countSql = "SELECT COUNT(*) as total 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE {$whereClause}";
        $countResult = $this->db->fetch($countSql, $params);
        $total = $countResult['total'];

        // Récupérer les produits
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";

        $products = $this->db->fetchAll($sql, $params);

        return [
            'products' => $products,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }

    /**
     * Obtenir un produit par son ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";

        $product = $this->db->fetch($sql, [$id]);

        if ($product) {
            $product['images'] = $this->getImages($id);
            $product['videos'] = $this->getVideos($id);
            $product['sizes'] = json_decode($product['sizes'] ?? '[]', true);
        }

        return $product;
    }

    /**
     * Obtenir un produit par son slug
     */
    public function getBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.slug = ? AND p.status = 'active'";

        $product = $this->db->fetch($sql, [$slug]);

        if ($product) {
            $product['images'] = $this->getImages($product['id']);
            $product['videos'] = $this->getVideos($product['id']);
            $product['sizes'] = json_decode($product['sizes'] ?? '[]', true);
            
            // Incrémenter les vues
            $this->incrementViews($product['id']);
        }

        return $product;
    }

    /**
     * Obtenir les images d'un produit
     */
    public function getImages(int $productId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC",
            [$productId]
        );
    }

    /**
     * Obtenir les vidéos d'un produit
     */
    public function getVideos(int $productId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM product_videos WHERE product_id = ? ORDER BY sort_order ASC",
            [$productId]
        );
    }

    /**
     * Obtenir les produits featured
     */
    public function getFeatured(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' AND p.is_featured = 1
                ORDER BY p.created_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Obtenir les nouveautés
     */
    public function getNew(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' AND p.is_new = 1
                ORDER BY p.created_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Obtenir les bestsellers
     */
    public function getBestSellers(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating,
                       COALESCE((SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as reviews_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' AND p.is_bestseller = 1
                ORDER BY p.sales_count DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Alias pour getNew - Obtenir les nouveautés
     */
    public function getNewArrivals(int $limit = 8): array
    {
        return $this->getNew($limit);
    }

    /**
     * Obtenir les produits similaires
     */
    public function getSimilar(int $productId, int $limit = 4): array
    {
        $product = $this->getById($productId);
        if (!$product) return [];

        $sql = "SELECT p.*, c.name as category_name,
                       COALESCE((SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND is_approved = 1), 0) as avg_rating
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' 
                  AND p.id != ?
                  AND (p.category_id = ? OR p.genre = ? OR p.target = ?)
                ORDER BY RAND()
                LIMIT ?";

        return $this->db->fetchAll($sql, [
            $productId,
            $product['category_id'],
            $product['genre'],
            $product['target'],
            $limit
        ]);
    }

    /**
     * Recherche de produits
     */
    public function search(string $query, int $limit = 10): array
    {
        $searchTerm = '%' . $query . '%';
        
        $sql = "SELECT p.id, p.name, p.slug, p.price, p.main_image, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active'
                  AND (p.name LIKE ? OR p.short_description LIKE ? OR p.sku LIKE ?)
                ORDER BY 
                    CASE WHEN p.name LIKE ? THEN 1 ELSE 2 END,
                    p.name ASC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $query . '%', $limit]);
    }

    /**
     * Incrémenter les vues d'un produit
     */
    public function incrementViews(int $productId): void
    {
        $this->db->query("UPDATE products SET views_count = views_count + 1 WHERE id = ?", [$productId]);
    }

    /**
     * Créer un produit (Admin)
     */
    public function create(array $data): int
    {
        // Générer le slug s'il n'existe pas
        if (empty($data['slug'])) {
            $data['slug'] = Security::slugify($data['name']);
        }

        // Générer le SKU s'il n'existe pas
        if (empty($data['sku'])) {
            $data['sku'] = 'TV-' . strtoupper(substr(md5(uniqid()), 0, 8));
        }

        // Encoder les sizes en JSON si c'est un tableau
        if (isset($data['sizes']) && is_array($data['sizes'])) {
            $data['sizes'] = json_encode($data['sizes']);
        }

        return $this->db->insert('products', $data);
    }

    /**
     * Mettre à jour un produit (Admin)
     */
    public function update(int $id, array $data): bool
    {
        // Encoder les sizes en JSON si c'est un tableau
        if (isset($data['sizes']) && is_array($data['sizes'])) {
            $data['sizes'] = json_encode($data['sizes']);
        }

        return $this->db->update('products', $data, 'id = ?', [$id]) > 0;
    }

    /**
     * Supprimer un produit (Admin)
     */
    public function delete(int $id): bool
    {
        return $this->db->delete('products', 'id = ?', [$id]) > 0;
    }

    /**
     * Ajouter une image à un produit
     */
    public function addImage(int $productId, string $imagePath, bool $isPrimary = false): int
    {
        if ($isPrimary) {
            // Retirer le statut primary des autres images
            $this->db->update('product_images', ['is_primary' => 0], 'product_id = ?', [$productId]);
        }

        return $this->db->insert('product_images', [
            'product_id' => $productId,
            'image_path' => $imagePath,
            'is_primary' => $isPrimary ? 1 : 0
        ]);
    }

    /**
     * Ajouter une vidéo à un produit
     */
    public function addVideo(int $productId, string $videoPath, ?string $thumbnail = null): int
    {
        return $this->db->insert('product_videos', [
            'product_id' => $productId,
            'video_path' => $videoPath,
            'thumbnail' => $thumbnail
        ]);
    }

    /**
     * Obtenir les statistiques produits (Admin)
     */
    public function getStats(): array
    {
        return [
            'total' => $this->db->count('products'),
            'active' => $this->db->count('products', "status = 'active'"),
            'out_of_stock' => $this->db->count('products', "stock_quantity = 0 AND status = 'active'"),
            'low_stock' => $this->db->count('products', "stock_quantity <= low_stock_threshold AND stock_quantity > 0 AND status = 'active'")
        ];
    }
}
