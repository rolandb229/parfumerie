<?php
/**
 * TATAVERNIS - Page d'inscription
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
$formData = [];

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expirée. Veuillez réessayer.";
    } else {
        $formData = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'newsletter' => isset($_POST['newsletter'])
        ];

        // Verifier la confirmation du mot de passe
        if ($_POST['password'] !== ($_POST['password_confirm'] ?? '')) {
            $error = "Les mots de passe ne correspondent pas.";
        } elseif (!isset($_POST['terms'])) {
            $error = "Vous devez accepter les conditions générales.";
        } else {
            $result = $auth->register($formData);

            if ($result['success']) {
                $redirect = $_SESSION['redirect_after_login'] ?? '/compte.php';
                unset($_SESSION['redirect_after_login']);
                flash('success', 'Bienvenue chez TATAVERNIS ! Votre compte a été créé avec succès.');
                Security::redirect($redirect);
            } else {
                $error = implode('<br>', $result['errors']);
            }
        }
    }
}

$pageTitle = "Créer un compte";
$pageDescription = "Créez votre compte TATAVERNIS et profitez d'avantages exclusifs.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container mx-auto px-4 py-8">
        <nav class="breadcrumb flex items-center gap-2 text-sm text-gray-400 mb-4">
            <a href="index.php" class="hover:text-gold transition-colors">Accueil</a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-cream">Inscription</span>
        </nav>
    </div>
</section>

<!-- Register Section -->
<section class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <div class="bg-muted rounded-2xl p-8 lg:p-10">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-display font-semibold mb-2">Créer un compte</h1>
                    <p class="text-gray-400">Rejoignez la communauté TATAVERNIS</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="register-form">
                    <input type="hidden" name="action" value="register">
                    <?= Security::csrfField() ?>

                    <div class="space-y-5">
                        <!-- Name Row -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="first_name" class="block text-sm font-medium mb-2">Prénom <span class="text-red-400">*</span></label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    required
                                    placeholder="Votre prénom"
                                    class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                                    value="<?= Security::e($formData['first_name'] ?? '') ?>"
                                >
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="block text-sm font-medium mb-2">Nom <span class="text-red-400">*</span></label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    required
                                    placeholder="Votre nom"
                                    class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                                    value="<?= Security::e($formData['last_name'] ?? '') ?>"
                                >
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium mb-2">Adresse email <span class="text-red-400">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                placeholder="exemple@email.com"
                                class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                                value="<?= Security::e($formData['email'] ?? '') ?>"
                            >
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone" class="block text-sm font-medium mb-2">Téléphone</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="+225 XX XX XX XX XX"
                                class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                                value="<?= Security::e($formData['phone'] ?? '') ?>"
                            >
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="block text-sm font-medium mb-2">Mot de passe <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    minlength="8"
                                    placeholder="Minimum 8 caractères"
                                    class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 pr-12 focus:outline-none focus:border-gold transition-colors"
                                >
                                <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cream transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Au moins 8 caractères avec une majuscule, une minuscule et un chiffre</p>
                        </div>

                        <!-- Password Confirm -->
                        <div class="form-group">
                            <label for="password_confirm" class="block text-sm font-medium mb-2">Confirmer le mot de passe <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirm" 
                                    name="password_confirm" 
                                    required
                                    placeholder="Confirmez votre mot de passe"
                                    class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 pr-12 focus:outline-none focus:border-gold transition-colors"
                                >
                                <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cream transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="form-group">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="newsletter" class="w-4 h-4 mt-0.5 accent-gold rounded" <?= !empty($formData['newsletter']) ? 'checked' : '' ?>>
                                <span class="text-sm text-gray-400">Je souhaite recevoir les offres exclusives et nouveautés par email</span>
                            </label>
                        </div>

                        <!-- Terms -->
                        <div class="form-group">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="terms" required class="w-4 h-4 mt-0.5 accent-gold rounded">
                                <span class="text-sm text-gray-400">
                                    J'accepte les <a href="conditions-generales.php" class="text-gold hover:text-gold-light">conditions générales</a> 
                                    et la <a href="politique-confidentialite.php" class="text-gold hover:text-gold-light">politique de confidentialité</a>
                                    <span class="text-red-400">*</span>
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gold text-primary font-semibold py-3 px-6 rounded-lg hover:bg-gold-light transition-colors">
                            CRÉER MON COMPTE
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <p class="text-center mt-6 text-gray-400">
                    Vous avez déjà un compte ? 
                    <a href="connexion.php" class="text-gold hover:text-gold-light transition-colors">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle icon
            this.innerHTML = type === 'password' 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
        });
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');

    passwordConfirm?.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            this.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
