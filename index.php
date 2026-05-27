<?php
/**
 * TATAVERNIS - Page d'accueil
 * L'Art de Sublimer Chaque Essence
 */

require_once 'config/config.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

$product = new Product();
$category = new Category();

// Récupérer les données pour la page d'accueil
$featuredProducts = $product->getFeatured(8);
$bestSellers = $product->getBestSellers(5);
$newArrivals = $product->getNewArrivals(4);
$categories = $category->getAll();

$pageTitle = "Accueil";
$pageDescription = "TATAVERNIS - L'art de sublimer chaque essence. Découvrez nos parfums d'exception, créations uniques inspirées de l'élégance et de la magie des senteurs.";

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image: url('assets/images/hero/hero-1.jpg');">
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content" data-aos="fade-up">
                            <span class="hero-subtitle">Collection Exclusive</span>
                            <h1 class="hero-title">L'ART DE SUBLIMER<br>CHAQUE ESSENCE</h1>
                            <p class="hero-description">Des créations uniques, inspirées de l'élégance et de la magie des senteurs.</p>
                            <div class="hero-buttons">
                                <a href="boutique.php" class="btn btn-primary btn-lg">
                                    <span>Découvrir nos Collections</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="a-propos.php" class="btn btn-outline btn-lg">
                                    <span>Notre Histoire</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="hero-slide" style="background-image: url('assets/images/hero/hero-2.jpg');">
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content" data-aos="fade-up">
                            <span class="hero-subtitle">Nouveauté</span>
                            <h1 class="hero-title">OUD ROYAL<br>L'EXCELLENCE INCARNÉE</h1>
                            <p class="hero-description">Un parfum oriental boisé, intense et mystérieux qui incarne la puissance et le raffinement.</p>
                            <div class="hero-buttons">
                                <a href="produit.php?slug=oud-royal" class="btn btn-primary btn-lg">
                                    <span>Découvrir</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Trust Badges -->
<section class="trust-badges">
    <div class="container">
        <div class="badges-grid">
            <div class="badge-item" data-aos="fade-up" data-aos-delay="0">
                <div class="badge-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="badge-content">
                    <h4>Livraison Rapide</h4>
                    <p>Partout dans le pays</p>
                </div>
            </div>
            <div class="badge-item" data-aos="fade-up" data-aos-delay="100">
                <div class="badge-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="badge-content">
                    <h4>Paiement Sécurisé</h4>
                    <p>100% sécurisé</p>
                </div>
            </div>
            <div class="badge-item" data-aos="fade-up" data-aos-delay="200">
                <div class="badge-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="badge-content">
                    <h4>Produits Authentiques</h4>
                    <p>Qualité garantie</p>
                </div>
            </div>
            <div class="badge-item" data-aos="fade-up" data-aos-delay="300">
                <div class="badge-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="badge-content">
                    <h4>Service Client</h4>
                    <p>À votre écoute</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Collections Section -->
<section class="collections-section section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Nos Collections</h2>
            <a href="collections.php" class="section-link">
                Voir toutes <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="collections-grid">
            <div class="collection-card collection-large" data-aos="fade-up" data-aos-delay="0">
                <a href="boutique.php?categorie=parfums-homme">
                    <div class="collection-image">
                        <img src="assets/images/collections/homme.jpg" alt="Parfums Homme">
                        <div class="collection-overlay"></div>
                    </div>
                    <div class="collection-content">
                        <h3>Parfums Homme</h3>
                        <p>Élégance & Caractère</p>
                    </div>
                </a>
            </div>
            <div class="collection-card" data-aos="fade-up" data-aos-delay="100">
                <a href="boutique.php?categorie=parfums-femme">
                    <div class="collection-image">
                        <img src="assets/images/collections/femme.jpg" alt="Parfums Femme">
                        <div class="collection-overlay"></div>
                    </div>
                    <div class="collection-content">
                        <h3>Parfums Femme</h3>
                        <p>Douceur & Séduction</p>
                    </div>
                </a>
            </div>
            <div class="collection-card" data-aos="fade-up" data-aos-delay="200">
                <a href="boutique.php?categorie=senteurs-unisexes">
                    <div class="collection-image">
                        <img src="assets/images/collections/unisexe.jpg" alt="Senteurs Unisexes">
                        <div class="collection-overlay"></div>
                    </div>
                    <div class="collection-content">
                        <h3>Senteurs Unisexes</h3>
                        <p>Équilibre & Harmonie</p>
                    </div>
                </a>
            </div>
            <div class="collection-card" data-aos="fade-up" data-aos-delay="300">
                <a href="boutique.php?categorie=senteurs-maison">
                    <div class="collection-image">
                        <img src="assets/images/collections/maison.jpg" alt="Senteurs Maison">
                        <div class="collection-overlay"></div>
                    </div>
                    <div class="collection-content">
                        <h3>Senteurs Maison</h3>
                        <p>Ambiance & Bien-être</p>
                    </div>
                </a>
            </div>
            <div class="collection-card" data-aos="fade-up" data-aos-delay="400">
                <a href="boutique.php?categorie=coffrets-prestige">
                    <div class="collection-image">
                        <img src="assets/images/collections/coffrets.jpg" alt="Coffrets Prestige">
                        <div class="collection-overlay"></div>
                    </div>
                    <div class="collection-content">
                        <h3>Coffrets Prestige</h3>
                        <p>Luxe & Raffinement</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="bestsellers-section section-padding bg-dark-alt">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Nos Meilleures Ventes</h2>
            <a href="boutique.php?tri=populaire" class="section-link">
                Voir toutes <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="products-slider swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php if (!empty($bestSellers)): ?>
                    <?php foreach ($bestSellers as $prod): ?>
                        <div class="swiper-slide">
                            <?php include 'includes/components/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Produits de démonstration -->
                    <?php
                    $demoProducts = [
                        ['id' => 1, 'name' => 'Oud Royal', 'slug' => 'oud-royal', 'price' => 120000, 'sale_price' => null, 'image' => 'assets/images/products/oud-royal.jpg', 'rating' => 4.8, 'reviews_count' => 48, 'is_new' => true, 'category' => 'Extrait de Parfum'],
                        ['id' => 2, 'name' => 'Vanille Noire', 'slug' => 'vanille-noire', 'price' => 80000, 'sale_price' => null, 'image' => 'assets/images/products/vanille-noire.jpg', 'rating' => 4.6, 'reviews_count' => 36, 'is_new' => true, 'category' => 'Eau de Parfum'],
                        ['id' => 3, 'name' => 'Ambre Éternel', 'slug' => 'ambre-eternel', 'price' => 85000, 'sale_price' => null, 'image' => 'assets/images/products/ambre-eternel.jpg', 'rating' => 4.7, 'reviews_count' => 42, 'is_new' => false, 'category' => 'Extrait de Parfum'],
                        ['id' => 4, 'name' => 'Velvet Rose', 'slug' => 'velvet-rose', 'price' => 85000, 'sale_price' => null, 'image' => 'assets/images/products/velvet-rose.jpg', 'rating' => 4.5, 'reviews_count' => 31, 'is_new' => true, 'category' => 'Eau de Parfum'],
                        ['id' => 5, 'name' => 'Citrus Gold', 'slug' => 'citrus-gold', 'price' => 75000, 'sale_price' => null, 'image' => 'assets/images/products/citrus-gold.jpg', 'rating' => 4.4, 'reviews_count' => 27, 'is_new' => false, 'category' => 'Eau de Parfum'],
                    ];
                    foreach ($demoProducts as $prod):
                    ?>
                        <div class="swiper-slide">
                            <div class="product-card">
                                <?php if (!empty($prod['is_new'])): ?>
                                    <span class="product-badge badge-new">Nouveau</span>
                                <?php endif; ?>
                                <div class="product-actions">
                                    <button class="action-btn btn-wishlist" data-product-id="<?= $prod['id'] ?>" title="Ajouter aux favoris">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="action-btn btn-quickview" data-product-id="<?= $prod['id'] ?>" title="Aperçu rapide">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <a href="produit.php?slug=<?= $prod['slug'] ?>" class="product-image">
                                    <img src="<?= $prod['image'] ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                                </a>
                                <div class="product-info">
                                    <span class="product-category"><?= $prod['category'] ?></span>
                                    <h3 class="product-name">
                                        <a href="produit.php?slug=<?= $prod['slug'] ?>"><?= htmlspecialchars($prod['name']) ?></a>
                                    </h3>
                                    <div class="product-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= floor($prod['rating'])): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif ($i - 0.5 <= $prod['rating']): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="rating-count">(<?= $prod['reviews_count'] ?>)</span>
                                    </div>
                                    <div class="product-price">
                                        <?php if (!empty($prod['sale_price'])): ?>
                                            <span class="price-old"><?= formatPrice($prod['price']) ?></span>
                                            <span class="price-current"><?= formatPrice($prod['sale_price']) ?></span>
                                        <?php else: ?>
                                            <span class="price-current"><?= formatPrice($prod['price']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <button class="btn btn-add-cart" data-product-id="<?= $prod['id'] ?>">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Ajouter au panier</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="story-section section-padding">
    <div class="container">
        <div class="story-grid">
            <div class="story-image" data-aos="fade-right">
                <img src="assets/images/story/atelier.jpg" alt="L'Atelier TATAVERNIS">
                <div class="story-badge">
                    <span class="years">10+</span>
                    <span class="text">Années d'Excellence</span>
                </div>
            </div>
            <div class="story-content" data-aos="fade-left">
                <span class="story-subtitle">L'HISTOIRE DE TATAVERNIS</span>
                <h2 class="story-title">Une signature olfactive, un héritage</h2>
                <p class="story-text">
                    Tatavernis est née d'une passion pour l'art du parfum et la recherche de l'excellence. 
                    Chaque création raconte une histoire, chaque senteur éveille une émotion.
                </p>
                <div class="story-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Ingrédients Nobles</h4>
                            <p>Sélectionnés avec soin</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Créations Uniques</h4>
                            <p>Inspirées par l'émotion</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Savoir-faire Artisanal</h4>
                            <p>Passion & précision</p>
                        </div>
                    </div>
                </div>
                <a href="a-propos.php" class="btn btn-primary">
                    <span>Découvrir notre histoire</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="new-arrivals-section section-padding bg-dark-alt">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Nouveautés</h2>
            <a href="boutique.php?tri=recent" class="section-link">
                Voir toutes <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="products-grid" data-aos="fade-up">
            <?php
            $newProducts = [
                ['id' => 6, 'name' => 'Bois Précieux', 'slug' => 'bois-precieux', 'price' => 90000, 'sale_price' => null, 'image' => 'assets/images/products/bois-precieux.jpg', 'rating' => 4.6, 'reviews_count' => 29, 'is_new' => true, 'category' => 'Extrait de Parfum'],
                ['id' => 7, 'name' => 'Musc Blanc', 'slug' => 'musc-blanc', 'price' => 70000, 'sale_price' => null, 'image' => 'assets/images/products/musc-blanc.jpg', 'rating' => 4.5, 'reviews_count' => 22, 'is_new' => true, 'category' => 'Eau de Parfum'],
                ['id' => 8, 'name' => 'Lumière Blanche', 'slug' => 'lumiere-blanche', 'price' => 100000, 'sale_price' => null, 'image' => 'assets/images/products/lumiere-blanche.jpg', 'rating' => 4.8, 'reviews_count' => 33, 'is_new' => true, 'category' => 'Eau de Parfum'],
                ['id' => 9, 'name' => 'Nuit Orientale', 'slug' => 'nuit-orientale', 'price' => 95000, 'sale_price' => null, 'image' => 'assets/images/products/nuit-orientale.jpg', 'rating' => 4.7, 'reviews_count' => 25, 'is_new' => true, 'category' => 'Extrait de Parfum'],
            ];
            foreach ($newProducts as $prod):
            ?>
                <div class="product-card">
                    <span class="product-badge badge-new">Nouveau</span>
                    <div class="product-actions">
                        <button class="action-btn btn-wishlist" data-product-id="<?= $prod['id'] ?>" title="Ajouter aux favoris">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="action-btn btn-quickview" data-product-id="<?= $prod['id'] ?>" title="Aperçu rapide">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <a href="produit.php?slug=<?= $prod['slug'] ?>" class="product-image">
                        <img src="<?= $prod['image'] ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                    </a>
                    <div class="product-info">
                        <span class="product-category"><?= $prod['category'] ?></span>
                        <h3 class="product-name">
                            <a href="produit.php?slug=<?= $prod['slug'] ?>"><?= htmlspecialchars($prod['name']) ?></a>
                        </h3>
                        <div class="product-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($prod['rating'])): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i - 0.5 <= $prod['rating']): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="rating-count">(<?= $prod['reviews_count'] ?>)</span>
                        </div>
                        <div class="product-price">
                            <span class="price-current"><?= formatPrice($prod['price']) ?></span>
                        </div>
                    </div>
                    <button class="btn btn-add-cart" data-product-id="<?= $prod['id'] ?>">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Ajouter au panier</span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section section-padding">
    <div class="container">
        <div class="newsletter-content" data-aos="fade-up">
            <div class="newsletter-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h2 class="newsletter-title">Rejoignez l'Univers TATAVERNIS</h2>
            <p class="newsletter-text">Inscrivez-vous à notre newsletter pour recevoir en avant-première nos nouveautés, conseils et offres exclusives.</p>
            <form class="newsletter-form" id="newsletter-form">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Votre adresse email" required>
                    <button type="submit" class="btn btn-primary">
                        <span>S'inscrire</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            <p class="newsletter-disclaimer">En vous inscrivant, vous acceptez de recevoir nos communications. Désabonnement possible à tout moment.</p>
        </div>
    </div>
</section>

<!-- Instagram Section -->
<section class="instagram-section section-padding bg-dark-alt">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Suivez-nous sur Instagram</h2>
            <a href="https://instagram.com/tatavernis" target="_blank" class="instagram-handle">@tatavernis</a>
        </div>
        
        <div class="instagram-grid" data-aos="fade-up">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <a href="https://instagram.com/tatavernis" target="_blank" class="instagram-item">
                    <img src="assets/images/instagram/insta-<?= $i ?>.jpg" alt="Instagram TATAVERNIS">
                    <div class="instagram-overlay">
                        <i class="fab fa-instagram"></i>
                    </div>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
