<?php
/**
 * Navigation principale
 * TATAVERNIS - Maison de Parfumerie Premium
 */

// Initialiser les objets nécessaires
$auth = Auth::getInstance();
$cart = new Cart();
$categoryModel = new Category();

$user = $auth->user();
$cartCount = $cart->getCount();
$categories = $categoryModel->getFeatured();
?>

<!-- Top Bar -->
<div class="bg-muted text-sm py-2 hidden md:block">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <p class="text-gray-400">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            Livraison gratuite à partir de <?= formatPrice(FREE_SHIPPING_THRESHOLD) ?>
        </p>
        <div class="flex items-center gap-6">
            <a href="tel:<?= SITE_PHONE ?>" class="text-gray-400 hover:text-gold transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <?= SITE_PHONE ?>
            </a>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<header class="sticky top-0 z-50 bg-primary/95 backdrop-blur-md border-b border-muted">
    <div class="container mx-auto px-4">
        <nav class="flex items-center justify-between h-20">
            
            <!-- Logo -->
            <a href="<?= url() ?>" class="flex-shrink-0">
                <img 
                    src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/nouveau_logo_tata_vernis_copie%5B1%5D-MYSK5d7Sa5b4cQPhYsEszEKpB1EC51.png" 
                    alt="<?= SITE_NAME ?>" 
                    class="h-10 md:h-12"
                >
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="<?= url() ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium <?= activeClass('index.php', 'text-gold') ?>">
                    Accueil
                </a>
                
                <!-- Parfums Dropdown -->
                <div class="relative group">
                    <button class="nav-link text-cream hover:text-gold transition-colors font-medium flex items-center gap-1 <?= activeClass('shop.php', 'text-gold') ?>">
                        Parfums
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <div class="bg-muted rounded-xl shadow-2xl border border-muted-light p-4 min-w-[200px]">
                            <a href="<?= url('shop.php') ?>" class="block px-4 py-2 rounded-lg hover:bg-gold/10 hover:text-gold transition-colors">
                                Tous les parfums
                            </a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="<?= url('shop.php?category=' . $cat['slug']) ?>" class="block px-4 py-2 rounded-lg hover:bg-gold/10 hover:text-gold transition-colors">
                                <?= Security::e($cat['name']) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <a href="<?= url('shop.php?category=senteurs-maison') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium">
                    Senteurs Maison
                </a>
                
                <a href="<?= url('shop.php?category=coffrets-prestige') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium">
                    Coffrets
                </a>
                
                <a href="<?= url('collections.php') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium">
                    Collections
                </a>
                
                <a href="<?= url('about.php') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium <?= activeClass('about.php', 'text-gold') ?>">
                    À propos
                </a>
                
                <a href="<?= url('blog.php') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium <?= activeClass('blog.php', 'text-gold') ?>">
                    Blog
                </a>
                
                <a href="<?= url('contact.php') ?>" class="nav-link text-cream hover:text-gold transition-colors font-medium <?= activeClass('contact.php', 'text-gold') ?>">
                    Contact
                </a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4">
                
                <!-- Search Button -->
                <button id="search-toggle" class="p-2 hover:bg-muted rounded-lg transition-colors" aria-label="Rechercher">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- User Account -->
                <?php if ($user): ?>
                <div class="relative group">
                    <button class="p-2 hover:bg-muted rounded-lg transition-colors flex items-center gap-2" aria-label="Mon compte">
                        <?php if ($user['avatar']): ?>
                            <img src="<?= uploadUrl($user['avatar']) ?>" alt="" class="w-8 h-8 rounded-full object-cover">
                        <?php else: ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        <?php endif; ?>
                        <span class="hidden md:inline text-sm"><?= Security::e($user['first_name']) ?></span>
                    </button>
                    <div class="absolute top-full right-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <div class="bg-muted rounded-xl shadow-2xl border border-muted-light p-2 min-w-[180px]">
                            <a href="<?= url('account.php') ?>" class="block px-4 py-2 rounded-lg hover:bg-gold/10 hover:text-gold transition-colors">
                                Mon compte
                            </a>
                            <a href="<?= url('account.php?tab=orders') ?>" class="block px-4 py-2 rounded-lg hover:bg-gold/10 hover:text-gold transition-colors">
                                Mes commandes
                            </a>
                            <a href="<?= url('account.php?tab=wishlist') ?>" class="block px-4 py-2 rounded-lg hover:bg-gold/10 hover:text-gold transition-colors">
                                Mes favoris
                            </a>
                            <hr class="my-2 border-muted-light">
                            <a href="<?= url('logout.php') ?>" class="block px-4 py-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-colors">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= url('login.php') ?>" class="p-2 hover:bg-muted rounded-lg transition-colors" aria-label="Connexion">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?= url('cart.php') ?>" class="p-2 hover:bg-muted rounded-lg transition-colors relative" aria-label="Panier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <?php if ($cartCount > 0): ?>
                    <span id="cart-count" class="absolute -top-1 -right-1 w-5 h-5 bg-gold text-primary text-xs font-bold rounded-full flex items-center justify-center">
                        <?= $cartCount ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 hover:bg-muted rounded-lg transition-colors" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed inset-0 z-50 lg:hidden hidden">
    <div class="absolute inset-0 bg-black/70" id="mobile-menu-overlay"></div>
    <div class="absolute right-0 top-0 h-full w-80 max-w-full bg-primary border-l border-muted transform translate-x-full transition-transform duration-300" id="mobile-menu-panel">
        <div class="p-4 border-b border-muted flex items-center justify-between">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/nouveau_logo_tata_vernis_copie%5B1%5D-MYSK5d7Sa5b4cQPhYsEszEKpB1EC51.png" alt="<?= SITE_NAME ?>" class="h-8">
            <button id="mobile-menu-close" class="p-2 hover:bg-muted rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="p-4 space-y-2">
            <a href="<?= url() ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Accueil</a>
            <a href="<?= url('shop.php') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Parfums</a>
            <a href="<?= url('shop.php?category=senteurs-maison') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Senteurs Maison</a>
            <a href="<?= url('shop.php?category=coffrets-prestige') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Coffrets</a>
            <a href="<?= url('collections.php') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Collections</a>
            <a href="<?= url('about.php') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">À propos</a>
            <a href="<?= url('blog.php') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Blog</a>
            <a href="<?= url('contact.php') ?>" class="block px-4 py-3 rounded-lg hover:bg-muted transition-colors">Contact</a>
        </nav>
        <?php if (!$user): ?>
        <div class="p-4 border-t border-muted">
            <a href="<?= url('login.php') ?>" class="block w-full py-3 bg-gold text-primary text-center font-semibold rounded-lg hover:bg-gold-light transition-colors">
                Connexion
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Search Modal -->
<div id="search-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80" id="search-modal-overlay"></div>
    <div class="relative max-w-2xl mx-auto mt-20 px-4">
        <div class="bg-muted rounded-2xl shadow-2xl overflow-hidden">
            <form action="<?= url('shop.php') ?>" method="GET" class="flex items-center">
                <input 
                    type="search" 
                    name="search" 
                    id="search-input"
                    placeholder="Rechercher un parfum..." 
                    class="flex-1 bg-transparent px-6 py-4 text-lg focus:outline-none"
                    autocomplete="off"
                >
                <button type="submit" class="p-4 text-gold hover:text-gold-light transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
            <!-- Search Results -->
            <div id="search-results" class="hidden border-t border-muted-light max-h-96 overflow-y-auto">
                <!-- Results will be injected here via AJAX -->
            </div>
        </div>
        <button id="search-modal-close" class="absolute -top-12 right-4 text-cream hover:text-gold transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
