<?php
/**
 * Classe Category - Gestion des catégories
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';

class Category
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir toutes les catégories
     */
    public function getAll(bool $activeOnly = true): array
    {
        $where = $activeOnly ? "WHERE status = 'active'" : "";
        return $this->db->fetchAll(
            "SELECT c.*, 
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = 'active') as products_count
             FROM categories c
             {$where}
             ORDER BY sort_order ASC, name ASC"
        );
    }

    /**
     * Obtenir les catégories featured
     */
    public function getFeatured(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, 
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = 'active') as products_count
             FROM categories c
             WHERE c.status = 'active' AND c.is_featured = 1
             ORDER BY c.sort_order ASC"
        );
    }

    /**
     * Obtenir une catégorie par son ID
     */
    public function getById(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT c.*, 
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = 'active') as products_count
             FROM categories c
             WHERE c.id = ?",
            [$id]
        );
    }

    /**
     * Obtenir une catégorie par son slug
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->db->fetch(
            "SELECT c.*, 
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = 'active') as products_count
             FROM categories c
             WHERE c.slug = ? AND c.status = 'active'",
            [$slug]
        );
    }

    /**
     * Créer une catégorie (Admin)
     */
    public function create(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = Security::slugify($data['name']);
        }

        return $this->db->insert('categories', $data);
    }

    /**
     * Mettre à jour une catégorie (Admin)
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->update('categories', $data, 'id = ?', [$id]) > 0;
    }

    /**
     * Supprimer une catégorie (Admin)
     */
    public function delete(int $id): bool
    {
        return $this->db->delete('categories', 'id = ?', [$id]) > 0;
    }
}
