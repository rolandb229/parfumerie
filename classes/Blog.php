<?php
/**
 * Classe Blog - Gestion des articles de blog
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';

class Blog
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir tous les articles publiés
     */
    public function getAll(array $filters = [], int $page = 1, int $perPage = BLOG_POSTS_PER_PAGE): array
    {
        $where = ["bp.status = 'published'", "bp.published_at <= NOW()"];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = "bp.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(bp.title LIKE ? OR bp.excerpt LIKE ? OR bp.content LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $total = $this->db->count('blog_posts bp', $whereClause, $params);

        $sql = "SELECT bp.*, a.first_name as author_first_name, a.last_name as author_last_name, a.avatar as author_avatar
                FROM blog_posts bp
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE {$whereClause}
                ORDER BY bp.published_at DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $posts = $this->db->fetchAll($sql, $params);

        // Décoder les tags JSON
        foreach ($posts as &$post) {
            $post['tags'] = json_decode($post['tags'] ?? '[]', true);
        }

        return [
            'posts' => $posts,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }

    /**
     * Obtenir un article par son slug
     */
    public function getBySlug(string $slug): ?array
    {
        $sql = "SELECT bp.*, a.first_name as author_first_name, a.last_name as author_last_name, a.avatar as author_avatar
                FROM blog_posts bp
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE bp.slug = ? AND bp.status = 'published' AND bp.published_at <= NOW()";

        $post = $this->db->fetch($sql, [$slug]);

        if ($post) {
            $post['tags'] = json_decode($post['tags'] ?? '[]', true);
            $post['media'] = $this->getMedia($post['id']);
            
            // Incrémenter les vues
            $this->incrementViews($post['id']);
        }

        return $post;
    }

    /**
     * Obtenir un article par son ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT bp.*, a.first_name as author_first_name, a.last_name as author_last_name
                FROM blog_posts bp
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE bp.id = ?";

        $post = $this->db->fetch($sql, [$id]);

        if ($post) {
            $post['tags'] = json_decode($post['tags'] ?? '[]', true);
            $post['media'] = $this->getMedia($post['id']);
        }

        return $post;
    }

    /**
     * Obtenir les médias d'un article
     */
    public function getMedia(int $postId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM blog_media WHERE post_id = ? ORDER BY sort_order ASC",
            [$postId]
        );
    }

    /**
     * Obtenir les articles featured
     */
    public function getFeatured(int $limit = 3): array
    {
        $sql = "SELECT bp.*, a.first_name as author_first_name, a.last_name as author_last_name
                FROM blog_posts bp
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE bp.status = 'published' AND bp.published_at <= NOW() AND bp.is_featured = 1
                ORDER BY bp.published_at DESC
                LIMIT ?";

        $posts = $this->db->fetchAll($sql, [$limit]);

        foreach ($posts as &$post) {
            $post['tags'] = json_decode($post['tags'] ?? '[]', true);
        }

        return $posts;
    }

    /**
     * Obtenir les articles récents
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT id, title, slug, excerpt, featured_image, published_at, reading_time
                FROM blog_posts
                WHERE status = 'published' AND published_at <= NOW()
                ORDER BY published_at DESC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Obtenir les catégories de blog
     */
    public function getCategories(): array
    {
        return $this->db->fetchAll(
            "SELECT category, COUNT(*) as count
             FROM blog_posts
             WHERE status = 'published' AND published_at <= NOW() AND category IS NOT NULL
             GROUP BY category
             ORDER BY count DESC"
        );
    }

    /**
     * Incrémenter les vues
     */
    public function incrementViews(int $postId): void
    {
        $this->db->query("UPDATE blog_posts SET views_count = views_count + 1 WHERE id = ?", [$postId]);
    }

    /**
     * Obtenir les commentaires d'un article
     */
    public function getComments(int $postId, bool $approvedOnly = true): array
    {
        $where = $approvedOnly ? "AND is_approved = 1" : "";
        
        return $this->db->fetchAll(
            "SELECT bc.*, u.first_name, u.last_name, u.avatar
             FROM blog_comments bc
             LEFT JOIN users u ON bc.user_id = u.id
             WHERE bc.post_id = ? AND bc.parent_id IS NULL {$where}
             ORDER BY bc.created_at DESC",
            [$postId]
        );
    }

    /**
     * Ajouter un commentaire
     */
    public function addComment(int $postId, array $data): array
    {
        $commentId = $this->db->insert('blog_comments', [
            'post_id' => $postId,
            'user_id' => $data['user_id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'author_name' => $data['name'] ?? null,
            'author_email' => $data['email'] ?? null,
            'content' => $data['content'],
            'is_approved' => 0 // Modération manuelle
        ]);

        // Incrémenter le compteur de commentaires
        $this->db->query(
            "UPDATE blog_posts SET comments_count = comments_count + 1 WHERE id = ?",
            [$postId]
        );

        return [
            'success' => true,
            'message' => "Votre commentaire a été soumis et sera publié après modération."
        ];
    }

    /**
     * Créer un article (Admin)
     */
    public function create(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = Security::slugify($data['title']);
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = json_encode($data['tags']);
        }

        // Calculer le temps de lecture
        if (!empty($data['content'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = max(1, ceil($wordCount / 200));
        }

        return $this->db->insert('blog_posts', $data);
    }

    /**
     * Mettre à jour un article (Admin)
     */
    public function update(int $id, array $data): bool
    {
        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = json_encode($data['tags']);
        }

        // Recalculer le temps de lecture
        if (!empty($data['content'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = max(1, ceil($wordCount / 200));
        }

        return $this->db->update('blog_posts', $data, 'id = ?', [$id]) > 0;
    }

    /**
     * Supprimer un article (Admin)
     */
    public function delete(int $id): bool
    {
        return $this->db->delete('blog_posts', 'id = ?', [$id]) > 0;
    }

    /**
     * Obtenir les statistiques blog (Admin)
     */
    public function getStats(): array
    {
        return [
            'total' => $this->db->count('blog_posts'),
            'published' => $this->db->count('blog_posts', "status = 'published'"),
            'draft' => $this->db->count('blog_posts', "status = 'draft'"),
            'total_views' => $this->db->fetch("SELECT COALESCE(SUM(views_count), 0) as total FROM blog_posts")['total']
        ];
    }
}
