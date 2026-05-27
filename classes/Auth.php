<?php
/**
 * Classe Auth - Authentification et gestion des sessions
 * TATAVERNIS - Maison de Parfumerie Premium
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';

class Auth
{
    private Database $db;
    private static ?Auth $instance = null;

    private function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function getInstance(): Auth
    {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    /**
     * Inscription d'un nouveau client
     */
    public function register(array $data): array
    {
        // Validation
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors[] = "Le prénom est requis.";
        }
        if (empty($data['last_name'])) {
            $errors[] = "Le nom est requis.";
        }
        if (empty($data['email']) || !Security::validateEmail($data['email'])) {
            $errors[] = "L'adresse email n'est pas valide.";
        }
        if (empty($data['password'])) {
            $errors[] = "Le mot de passe est requis.";
        } else {
            $passwordErrors = Security::validatePassword($data['password']);
            $errors = array_merge($errors, $passwordErrors);
        }
        if (!empty($data['phone']) && !Security::validatePhone($data['phone'])) {
            $errors[] = "Le numéro de téléphone n'est pas valide.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Vérifier si l'email existe déjà
        $existing = $this->db->fetch("SELECT id FROM users WHERE email = ?", [$data['email']]);
        if ($existing) {
            return ['success' => false, 'errors' => ["Cette adresse email est déjà utilisée."]];
        }

        // Créer l'utilisateur
        try {
            $userId = $this->db->insert('users', [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Security::hashPassword($data['password']),
                'newsletter' => !empty($data['newsletter']) ? 1 : 0
            ]);

            // Connecter automatiquement
            $this->loginById($userId);

            return ['success' => true, 'user_id' => $userId];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ["Une erreur est survenue lors de l'inscription."]];
        }
    }

    /**
     * Connexion client
     */
    public function login(string $email, string $password, bool $remember = false): array
    {
        // Rate limiting
        if (!Security::rateLimit('login_' . $email, 5, 15)) {
            return ['success' => false, 'errors' => ["Trop de tentatives. Réessayez dans 15 minutes."]];
        }

        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ? AND status = 'active'",
            [$email]
        );

        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            return ['success' => false, 'errors' => ["Email ou mot de passe incorrect."]];
        }

        // Mettre à jour la dernière connexion
        $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);

        // Créer la session
        $this->createSession($user, 'user');

        // Remember me
        if ($remember) {
            $token = Security::generateToken();
            $this->db->update('users', ['remember_token' => $token], 'id = ?', [$user['id']]);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        }

        return ['success' => true, 'user' => $user];
    }

    /**
     * Connexion admin
     */
    public function adminLogin(string $username, string $password): array
    {
        // Rate limiting
        if (!Security::rateLimit('admin_login_' . $username, 3, 30)) {
            return ['success' => false, 'errors' => ["Trop de tentatives. Réessayez dans 30 minutes."]];
        }

        $admin = $this->db->fetch(
            "SELECT * FROM admins WHERE (username = ? OR email = ?) AND status = 'active'",
            [$username, $username]
        );

        if (!$admin || !Security::verifyPassword($password, $admin['password'])) {
            return ['success' => false, 'errors' => ["Identifiants incorrects."]];
        }

        // Mettre à jour la dernière connexion
        $this->db->update('admins', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$admin['id']]);

        // Créer la session
        $this->createSession($admin, 'admin');

        return ['success' => true, 'admin' => $admin];
    }

    /**
     * Connexion par ID (après inscription)
     */
    public function loginById(int $userId): void
    {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        if ($user) {
            $this->createSession($user, 'user');
        }
    }

    /**
     * Créer la session utilisateur
     */
    private function createSession(array $userData, string $type): void
    {
        session_regenerate_id(true);
        
        $_SESSION['auth'] = [
            'id' => $userData['id'],
            'type' => $type,
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'full_name' => $userData['first_name'] . ' ' . $userData['last_name'],
            'avatar' => $userData['avatar'] ?? null,
            'logged_in_at' => time()
        ];

        if ($type === 'admin') {
            $_SESSION['auth']['role'] = $userData['role'];
            $_SESSION['auth']['username'] = $userData['username'];
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['auth']['id']);
    }

    /**
     * Vérifier si c'est un admin connecté
     */
    public function isAdmin(): bool
    {
        return $this->isLoggedIn() && ($_SESSION['auth']['type'] ?? '') === 'admin';
    }

    /**
     * Vérifier si c'est un super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->isAdmin() && ($_SESSION['auth']['role'] ?? '') === 'super_admin';
    }

    /**
     * Obtenir l'utilisateur courant
     */
    public function user(): ?array
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return $_SESSION['auth'];
    }

    /**
     * Obtenir l'ID de l'utilisateur courant
     */
    public function userId(): ?int
    {
        return $_SESSION['auth']['id'] ?? null;
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        // Supprimer le token remember_me
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }

        // Détruire la session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Demande de réinitialisation de mot de passe
     */
    public function requestPasswordReset(string $email): array
    {
        $user = $this->db->fetch("SELECT id, email, first_name FROM users WHERE email = ?", [$email]);
        
        if (!$user) {
            // Ne pas révéler si l'email existe
            return ['success' => true, 'message' => "Si cette adresse existe, vous recevrez un email."];
        }

        $token = Security::generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->update('users', [
            'reset_token' => $token,
            'reset_token_expires' => $expires
        ], 'id = ?', [$user['id']]);

        // TODO: Envoyer l'email avec le lien de réinitialisation
        // Pour l'instant, retourner le token (à supprimer en production)
        
        return [
            'success' => true,
            'message' => "Un email de réinitialisation a été envoyé.",
            'debug_token' => DEBUG_MODE ? $token : null
        ];
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(string $token, string $newPassword): array
    {
        $user = $this->db->fetch(
            "SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()",
            [$token]
        );

        if (!$user) {
            return ['success' => false, 'errors' => ["Le lien de réinitialisation est invalide ou expiré."]];
        }

        $passwordErrors = Security::validatePassword($newPassword);
        if (!empty($passwordErrors)) {
            return ['success' => false, 'errors' => $passwordErrors];
        }

        $this->db->update('users', [
            'password' => Security::hashPassword($newPassword),
            'reset_token' => null,
            'reset_token_expires' => null
        ], 'id = ?', [$user['id']]);

        return ['success' => true, 'message' => "Votre mot de passe a été mis à jour."];
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(int $userId, array $data): array
    {
        $allowedFields = ['first_name', 'last_name', 'phone', 'gender', 'birth_date', 'newsletter'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateData)) {
            return ['success' => false, 'errors' => ["Aucune donnée à mettre à jour."]];
        }

        try {
            $this->db->update('users', $updateData, 'id = ?', [$userId]);
            
            // Mettre à jour la session
            if (isset($updateData['first_name'])) {
                $_SESSION['auth']['first_name'] = $updateData['first_name'];
            }
            if (isset($updateData['last_name'])) {
                $_SESSION['auth']['last_name'] = $updateData['last_name'];
            }
            if (isset($updateData['first_name']) || isset($updateData['last_name'])) {
                $_SESSION['auth']['full_name'] = $_SESSION['auth']['first_name'] . ' ' . $_SESSION['auth']['last_name'];
            }

            return ['success' => true, 'message' => "Profil mis à jour avec succès."];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ["Une erreur est survenue."]];
        }
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        $user = $this->db->fetch("SELECT password FROM users WHERE id = ?", [$userId]);

        if (!$user || !Security::verifyPassword($currentPassword, $user['password'])) {
            return ['success' => false, 'errors' => ["Le mot de passe actuel est incorrect."]];
        }

        $passwordErrors = Security::validatePassword($newPassword);
        if (!empty($passwordErrors)) {
            return ['success' => false, 'errors' => $passwordErrors];
        }

        $this->db->update('users', [
            'password' => Security::hashPassword($newPassword)
        ], 'id = ?', [$userId]);

        return ['success' => true, 'message' => "Mot de passe modifié avec succès."];
    }

    /**
     * Middleware: Requiert une connexion
     */
    public function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            Security::redirect('/login.php');
        }
    }

    /**
     * Middleware: Requiert une connexion admin
     */
    public function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            Security::redirect('/admin/login.php');
        }
    }
}
