<?php
/**
 * Classe Security - Protection XSS, CSRF, etc.
 * TATAVERNIS - Maison de Parfumerie Premium
 */

class Security
{
    /**
     * Échapper les caractères HTML (protection XSS)
     */
    public static function escape($value): string
    {
        if (is_array($value)) {
            return array_map([self::class, 'escape'], $value);
        }
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Alias pour escape
     */
    public static function e($value): string
    {
        return self::escape($value);
    }

    /**
     * Nettoyer une chaîne
     */
    public static function clean(string $value): string
    {
        $value = trim($value);
        $value = stripslashes($value);
        return self::escape($value);
    }

    /**
     * Générer un token CSRF
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Obtenir le champ hidden CSRF pour les formulaires
     */
    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::generateCsrfToken() . '">';
    }

    /**
     * Vérifier le token CSRF
     */
    public static function verifyCsrfToken(?string $token = null): bool
    {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }
        
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Régénérer le token CSRF
     */
    public static function regenerateCsrfToken(): string
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    /**
     * Valider une adresse email
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valider un numéro de téléphone (format Côte d'Ivoire)
     */
    public static function validatePhone(string $phone): bool
    {
        // Accepte les formats: 0123456789, +225 01 23 45 67 89, etc.
        $phone = preg_replace('/[\s\-\.]/', '', $phone);
        return preg_match('/^(\+225)?[0-9]{10}$/', $phone) === 1;
    }

    /**
     * Nettoyer un numéro de téléphone
     */
    public static function cleanPhone(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    /**
     * Valider la force d'un mot de passe
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une minuscule.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
        }
        
        return $errors;
    }

    /**
     * Hasher un mot de passe
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Vérifier un mot de passe
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Générer un token aléatoire
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Générer un slug à partir d'une chaîne
     */
    public static function slugify(string $text): string
    {
        // Translittération des caractères accentués
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        // Convertir en minuscules
        $text = strtolower($text);
        // Remplacer les caractères non alphanumériques par des tirets
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        // Supprimer les tirets en début et fin
        $text = trim($text, '-');
        // Supprimer les tirets multiples
        $text = preg_replace('/-+/', '-', $text);
        
        return $text;
    }

    /**
     * Obtenir l'adresse IP du client
     */
    public static function getClientIp(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Prendre la première IP si plusieurs
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }

    /**
     * Redirection sécurisée
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        // Vérifier que l'URL est locale ou autorisée
        if (!filter_var($url, FILTER_VALIDATE_URL) && strpos($url, '/') === 0) {
            $url = SITE_URL . $url;
        }
        
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    /**
     * Vérifier si la requête est AJAX
     */
    public static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Réponse JSON
     */
    public static function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Limiter le taux de requêtes (rate limiting simple)
     */
    public static function rateLimit(string $key, int $maxAttempts = 5, int $decayMinutes = 1): bool
    {
        $cacheKey = 'rate_limit_' . md5($key . self::getClientIp());
        
        if (!isset($_SESSION[$cacheKey])) {
            $_SESSION[$cacheKey] = [
                'attempts' => 0,
                'expires' => time() + ($decayMinutes * 60)
            ];
        }
        
        // Réinitialiser si expiré
        if (time() > $_SESSION[$cacheKey]['expires']) {
            $_SESSION[$cacheKey] = [
                'attempts' => 0,
                'expires' => time() + ($decayMinutes * 60)
            ];
        }
        
        $_SESSION[$cacheKey]['attempts']++;
        
        return $_SESSION[$cacheKey]['attempts'] <= $maxAttempts;
    }
}
