<?php
/**
 * TATAVERNIS - Page de Connexion
 */

require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Security.php';

$auth = Auth::getInstance();

// Si deja connecte, rediriger
if ($auth->isLoggedIn()) {
    Security::redirect('/compte.php');
}

$error = '';
$success = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expirée. Veuillez réessayer.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $result = $auth->login($email, $password, $remember);

        if ($result['success']) {
            $redirect = $_SESSION['redirect_after_login'] ?? '/compte.php';
            unset($_SESSION['redirect_after_login']);
            Security::redirect($redirect);
        } else {
            $error = implode('<br>', $result['errors']);
        }
    }
}

$pageTitle = "Connexion";
$pageDescription = "Connectez-vous à votre compte TATAVERNIS pour accéder à vos commandes et favoris.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container mx-auto px-4 py-8">
        <nav class="breadcrumb flex items-center gap-2 text-sm text-gray-400 mb-4">
            <a href="index.php" class="hover:text-gold transition-colors">Accueil</a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-cream">Connexion</span>
        </nav>
    </div>
</section>

<!-- Login Section -->
<section class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
                
                <!-- Login Form -->
                <div class="bg-muted rounded-2xl p-8 lg:p-10">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-display font-semibold mb-2">Connexion</h1>
                        <p class="text-gray-400">Bienvenue chez Tatavernis<br>Connectez-vous à votre compte</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="login-form">
                        <input type="hidden" name="action" value="login">
                        <?= Security::csrfField() ?>

                        <div class="space-y-5">
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium mb-2">Email ou téléphone</label>
                                <input 
                                    type="text" 
                                    id="email" 
                                    name="email" 
                                    required
                                    placeholder="Entrez votre email ou téléphone"
                                    class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                                    value="<?= Security::e($_POST['email'] ?? '') ?>"
                                >
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        required
                                        placeholder="Entrez votre mot de passe"
                                        class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 pr-12 focus:outline-none focus:border-gold transition-colors"
                                    >
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cream transition-colors" id="toggle-password">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="remember" class="w-4 h-4 accent-gold rounded">
                                    <span class="text-sm text-gray-400">Se souvenir de moi</span>
                                </label>
                                <a href="mot-de-passe-oublie.php" class="text-sm text-gold hover:text-gold-light transition-colors">
                                    Mot de passe oublié ?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gold text-primary font-semibold py-3 px-6 rounded-lg hover:bg-gold-light transition-colors">
                                SE CONNECTER
                            </button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <p class="text-center mt-6 text-gray-400">
                        Vous n'avez pas de compte ? 
                        <a href="inscription.php" class="text-gold hover:text-gold-light transition-colors">Créer un compte</a>
                    </p>
                </div>

                <!-- Benefits -->
                <div class="flex flex-col justify-center">
                    <h2 class="text-2xl font-display font-semibold mb-6">Pourquoi créer un compte ?</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Suivi facile de vos commandes</h4>
                                <p class="text-gray-400 text-sm">Suivez vos commandes en temps réel et accédez à votre historique d'achats.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Sauvegarde de vos adresses</h4>
                                <p class="text-gray-400 text-sm">Enregistrez vos adresses pour un paiement plus rapide.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Accès à vos favoris</h4>
                                <p class="text-gray-400 text-sm">Créez votre liste de parfums préférés et retrouvez-les facilement.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Offres exclusives pour nos clients</h4>
                                <p class="text-gray-400 text-sm">Bénéficiez de promotions et d'avantages réservés aux membres.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    
    togglePassword?.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        this.innerHTML = type === 'password' 
            ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>'
            : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
