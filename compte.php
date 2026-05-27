<?php
/**
 * TATAVERNIS - Page Compte Client
 */

require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Order.php';
require_once 'classes/Security.php';

$auth = Auth::getInstance();
$auth->requireAuth();

$user = $auth->user();
$tab = $_GET['tab'] ?? 'dashboard';

// Instancier les classes necessaires
$orderModel = new Order();

// Recuperer les donnees selon l'onglet
$orders = [];
$wishlist = [];

if ($tab === 'orders' || $tab === 'dashboard') {
    $orders = $orderModel->getByUser($auth->userId(), 5);
}

$pageTitle = "Mon Compte";
$pageDescription = "Gérez votre compte TATAVERNIS, vos commandes et vos favoris.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container mx-auto px-4 py-8">
        <nav class="breadcrumb flex items-center gap-2 text-sm text-gray-400 mb-4">
            <a href="index.php" class="hover:text-gold transition-colors">Accueil</a>
            <span><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="text-cream">Mon compte</span>
        </nav>
    </div>
</section>

<!-- Account Section -->
<section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-muted rounded-2xl p-6">
                    <!-- User Info -->
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-muted-light">
                        <div class="w-14 h-14 bg-gold/20 rounded-full flex items-center justify-center">
                            <?php if ($user['avatar']): ?>
                                <img src="<?= uploadUrl($user['avatar']) ?>" alt="" class="w-full h-full object-cover rounded-full">
                            <?php else: ?>
                                <span class="text-xl font-display font-semibold text-gold"><?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-semibold"><?= Security::e($user['full_name']) ?></p>
                            <p class="text-sm text-gray-400">Admin Tatavernis</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="space-y-1">
                        <a href="?tab=dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'dashboard' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                        <a href="?tab=orders" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'orders' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Commandes</span>
                        </a>
                        <a href="?tab=wishlist" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'wishlist' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Favoris</span>
                        </a>
                        <a href="?tab=addresses" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'addresses' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Adresses</span>
                        </a>
                        <a href="?tab=profile" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'profile' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil</span>
                        </a>
                        <a href="?tab=security" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $tab === 'security' ? 'bg-gold text-primary' : 'hover:bg-muted-light' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Sécurité</span>
                        </a>
                        <hr class="my-4 border-muted-light">
                        <a href="deconnexion.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Déconnexion</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <?= displayFlashes() ?>

                <?php if ($tab === 'dashboard'): ?>
                    <!-- Dashboard -->
                    <div class="space-y-6">
                        <h1 class="text-2xl font-display font-semibold">Tableau de bord</h1>
                        
                        <!-- Stats -->
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-muted rounded-xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold"><?= count($orders) ?></p>
                                        <p class="text-sm text-gray-400">Commandes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-muted rounded-xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">0</p>
                                        <p class="text-sm text-gray-400">Favoris</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-muted rounded-xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">0</p>
                                        <p class="text-sm text-gray-400">En attente</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-muted rounded-xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gold/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">0</p>
                                        <p class="text-sm text-gray-400">Livrées</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div class="bg-muted rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold">Commandes récentes</h2>
                                <a href="?tab=orders" class="text-gold text-sm hover:text-gold-light transition-colors">Voir tout</a>
                            </div>

                            <?php if (empty($orders)): ?>
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <p class="text-gray-400">Aucune commande pour le moment</p>
                                    <a href="boutique.php" class="inline-block mt-4 text-gold hover:text-gold-light transition-colors">Découvrir nos parfums</a>
                                </div>
                            <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="text-left text-sm text-gray-400 border-b border-muted-light">
                                                <th class="pb-3">Commande</th>
                                                <th class="pb-3">Date</th>
                                                <th class="pb-3">Statut</th>
                                                <th class="pb-3 text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-muted-light">
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td class="py-4">
                                                        <a href="commande.php?id=<?= $order['id'] ?>" class="text-gold hover:text-gold-light">
                                                            #<?= $order['order_number'] ?>
                                                        </a>
                                                    </td>
                                                    <td class="py-4 text-gray-400"><?= formatDate($order['created_at']) ?></td>
                                                    <td class="py-4"><?= orderStatusBadge($order['status']) ?></td>
                                                    <td class="py-4 text-right font-semibold"><?= formatPrice($order['total']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($tab === 'profile'): ?>
                    <!-- Profile -->
                    <div class="space-y-6">
                        <h1 class="text-2xl font-display font-semibold">Mon profil</h1>
                        
                        <div class="bg-muted rounded-2xl p-6">
                            <form method="POST" action="api/profile.php" id="profile-form">
                                <?= Security::csrfField() ?>
                                <div class="grid sm:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Prénom</label>
                                        <input type="text" name="first_name" value="<?= Security::e($user['first_name']) ?>" 
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Nom</label>
                                        <input type="text" name="last_name" value="<?= Security::e($user['last_name']) ?>" 
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Email</label>
                                        <input type="email" value="<?= Security::e($user['email']) ?>" disabled
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 text-gray-500 cursor-not-allowed">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Téléphone</label>
                                        <input type="tel" name="phone" value="" placeholder="+225 XX XX XX XX XX"
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" class="bg-gold text-primary font-semibold px-6 py-3 rounded-lg hover:bg-gold-light transition-colors">
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php elseif ($tab === 'security'): ?>
                    <!-- Security -->
                    <div class="space-y-6">
                        <h1 class="text-2xl font-display font-semibold">Sécurité</h1>
                        
                        <div class="bg-muted rounded-2xl p-6">
                            <h3 class="font-semibold mb-4">Changer le mot de passe</h3>
                            <form method="POST" action="api/password.php" id="password-form">
                                <?= Security::csrfField() ?>
                                <div class="space-y-4 max-w-md">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Mot de passe actuel</label>
                                        <input type="password" name="current_password" required
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Nouveau mot de passe</label>
                                        <input type="password" name="new_password" required minlength="8"
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium mb-2">Confirmer le nouveau mot de passe</label>
                                        <input type="password" name="confirm_password" required
                                               class="w-full bg-primary border border-muted-light rounded-lg px-4 py-3 focus:outline-none focus:border-gold transition-colors">
                                    </div>
                                    <button type="submit" class="bg-gold text-primary font-semibold px-6 py-3 rounded-lg hover:bg-gold-light transition-colors">
                                        Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Default / Orders / Wishlist / Addresses -->
                    <div class="text-center py-12">
                        <p class="text-gray-400">Cette section est en cours de développement.</p>
                        <a href="?tab=dashboard" class="inline-block mt-4 text-gold hover:text-gold-light transition-colors">Retour au tableau de bord</a>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
