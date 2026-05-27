<?php
/**
 * TATAVERNIS - Admin Login
 */

require_once '../config/config.php';
require_once '../classes/Auth.php';
require_once '../classes/Security.php';

$auth = Auth::getInstance();

// Si deja connecte comme admin, rediriger
if ($auth->isAdmin()) {
    Security::redirect('/admin/index.php');
}

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Session expirée. Veuillez réessayer.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $result = $auth->adminLogin($username, $password);

        if ($result['success']) {
            Security::redirect('/admin/index.php');
        } else {
            $error = implode('<br>', $result['errors']);
        }
    }
}

$pageTitle = "Connexion Admin";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::e($pageTitle) ?> - TATAVERNIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0B0B0B',
                        'gold': '#C8A96B',
                        'gold-light': '#D4B87A',
                        'cream': '#F8F5F0',
                        'muted': '#2C2C2C',
                        'muted-light': '#3C3C3C'
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'sans': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-primary text-cream min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/nouveau_logo_tata_vernis_copie%5B1%5D-MYSK5d7Sa5b4cQPhYsEszEKpB1EC51.png" alt="TATAVERNIS" class="h-12 mx-auto mb-6">
            <h1 class="text-2xl font-display font-semibold">Administration</h1>
            <p class="text-gray-400 mt-2">Connectez-vous à votre espace admin</p>
        </div>

        <div class="bg-muted rounded-2xl p-8">
            <?php if ($error): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <?= Security::csrfField() ?>

                <div class="space-y-5">
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Identifiant</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            placeholder="admin@tatavernis.com"
                            class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                            value="<?= Security::e($_POST['username'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Votre mot de passe"
                            class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors"
                        >
                    </div>

                    <button type="submit" class="w-full bg-gold text-primary font-semibold py-3 px-6 rounded-lg hover:bg-gold-light transition-colors">
                        SE CONNECTER
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-gray-500 text-sm">
            <a href="../index.php" class="hover:text-gold transition-colors">Retour au site</a>
        </p>
    </div>
</body>
</html>
