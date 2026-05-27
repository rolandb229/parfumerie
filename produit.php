<?php
/**
 * TATAVERNIS - Fiche Produit
 */

require_once 'config/config.php';
require_once 'classes/Product.php';
require_once 'classes/Cart.php';

$product = new Product();

// Récupérer le produit
$slug = $_GET['slug'] ?? null;
if (!$slug) {
    header('Location: boutique.php');
    exit;
}

$prod = $product->getBySlug($slug);

// Si pas de produit en base, utiliser des données de démo
if (!$prod) {
    $demoProducts = [
        'oud-royal' => [
            'id' => 1,
            'name' => 'Oud Royal',
            'slug' => 'oud-royal',
            'short_description' => 'Un parfum oriental boisé, intense et mystérieux.',
            'description' => '<p>Oud Royal incarne la puissance et le raffinement. Ce parfum d\'exception révèle un accord unique entre l\'oud le plus précieux et des notes épicées qui évoquent les palais d\'Orient.</p><p>Une fragrance envoûtante qui laisse une empreinte mémorable, symbole d\'élégance et de sophistication absolue.</p>',
            'price' => 120000,
            'sale_price' => null,
            'category_name' => 'Extrait de Parfum',
            'genre' => 'Unisexe',
            'concentration' => 'Extrait de Parfum',
            'volume' => '100ml',
            'rating' => 4.8,
            'reviews_count' => 48,
            'stock' => 25,
            'is_new' => false,
            'sku' => 'TV-OUD-001',
            'images' => [
                'assets/images/products/oud-royal.jpg',
                'assets/images/products/oud-royal-2.jpg',
                'assets/images/products/oud-royal-3.jpg',
            ],
            'notes' => [
                'top' => ['Safran', 'Bergamote'],
                'heart' => ['Oud', 'Rose', 'Patchouli'],
                'base' => ['Ambre', 'Musc', 'Vanille']
            ],
            'variants' => [
                ['id' => 1, 'volume' => '50 ml', 'price' => 75000],
                ['id' => 2, 'volume' => '100 ml', 'price' => 120000],
                ['id' => 3, 'volume' => '150 ml', 'price' => 165000],
            ],
            'video_url' => null,
        ],
        'vanille-noire' => [
            'id' => 2,
            'name' => 'Vanille Noire',
            'slug' => 'vanille-noire',
            'short_description' => 'Une vanille envoûtante aux accents gourmands.',
            'description' => '<p>Vanille Noire est une interprétation moderne et séduisante de la vanille. Loin des clichés sucrés, cette création révèle une facette sombre et mystérieuse de cette note tant appréciée.</p>',
            'price' => 80000,
            'sale_price' => null,
            'category_name' => 'Eau de Parfum',
            'genre' => 'Femme',
            'concentration' => 'Eau de Parfum',
            'volume' => '100ml',
            'rating' => 4.6,
            'reviews_count' => 36,
            'stock' => 42,
            'is_new' => true,
            'sku' => 'TV-VAN-002',
            'images' => ['assets/images/products/vanille-noire.jpg'],
            'notes' => [
                'top' => ['Bergamote', 'Poivre Rose'],
                'heart' => ['Vanille Absolue', 'Jasmin'],
                'base' => ['Bois de Santal', 'Musc Blanc']
            ],
            'variants' => [
                ['id' => 1, 'volume' => '50 ml', 'price' => 50000],
                ['id' => 2, 'volume' => '100 ml', 'price' => 80000],
            ],
            'video_url' => null,
        ],
    ];
    
    $prod = $demoProducts[$slug] ?? null;
    
    if (!$prod) {
        header('Location: boutique.php');
        exit;
    }
}

// Produits similaires
$relatedProducts = $product->getRelated($prod['id'] ?? 1, 4);

$pageTitle = $prod['name'];
$pageDescription = $prod['short_description'] ?? $prod['description'];

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<section class="page-header page-header-sm">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Accueil</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <a href="boutique.php">Parfums</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span><?= htmlspecialchars($prod['name']) ?></span>
        </nav>
    </div>
</section>

<!-- Product Detail -->
<section class="product-detail section-padding">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Gallery -->
            <div class="product-gallery" data-aos="fade-right">
                <div class="gallery-main swiper" id="gallery-main">
                    <div class="swiper-wrapper">
                        <?php 
                        $images = $prod['images'] ?? ['assets/images/products/placeholder.jpg'];
                        foreach ($images as $image): 
                        ?>
                            <div class="swiper-slide">
                                <div class="gallery-image">
                                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                                    <button class="btn-zoom" data-fancybox="gallery" data-src="<?= $image ?>">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if (count($images) > 1): ?>
                    <div class="gallery-thumbs swiper" id="gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($prod['video_url'])): ?>
                    <div class="product-video">
                        <button class="btn-play-video" data-fancybox data-src="<?= $prod['video_url'] ?>">
                            <i class="fas fa-play"></i>
                            <span>Voir la vidéo</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Product Info -->
            <div class="product-info-detail" data-aos="fade-left">
                <div class="product-header">
                    <span class="product-category-badge"><?= htmlspecialchars($prod['category_name'] ?? 'Parfum') ?></span>
                    <h1 class="product-title"><?= htmlspecialchars($prod['name']) ?></h1>
                    
                    <div class="product-meta">
                        <div class="product-rating-detail">
                            <?php 
                            $rating = $prod['rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++): 
                            ?>
                                <?php if ($i <= floor($rating)): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i - 0.5 <= $rating): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="rating-value"><?= number_format($rating, 1) ?></span>
                            <a href="#reviews" class="rating-count">(<?= $prod['reviews_count'] ?? 0 ?> avis)</a>
                        </div>
                        <span class="product-sku">Réf: <?= htmlspecialchars($prod['sku'] ?? 'N/A') ?></span>
                    </div>
                </div>
                
                <div class="product-price-detail">
                    <?php if (!empty($prod['sale_price'])): ?>
                        <span class="price-old"><?= formatPrice($prod['price']) ?></span>
                        <span class="price-current"><?= formatPrice($prod['sale_price']) ?></span>
                        <span class="price-discount">-<?= round((1 - $prod['sale_price'] / $prod['price']) * 100) ?>%</span>
                    <?php else: ?>
                        <span class="price-current"><?= formatPrice($prod['price']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-short-desc">
                    <p><?= htmlspecialchars($prod['short_description'] ?? '') ?></p>
                </div>
                
                <!-- Olfactory Notes Preview -->
                <?php if (!empty($prod['notes'])): ?>
                    <div class="notes-preview">
                        <h4>Notes Olfactives</h4>
                        <div class="notes-list">
                            <div class="note-item">
                                <span class="note-label">Tête:</span>
                                <span class="note-value"><?= implode(', ', $prod['notes']['top']) ?></span>
                            </div>
                            <div class="note-item">
                                <span class="note-label">Cœur:</span>
                                <span class="note-value"><?= implode(', ', $prod['notes']['heart']) ?></span>
                            </div>
                            <div class="note-item">
                                <span class="note-label">Fond:</span>
                                <span class="note-value"><?= implode(', ', $prod['notes']['base']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Variants / Volume Selection -->
                <?php if (!empty($prod['variants'])): ?>
                    <div class="product-variants">
                        <label class="variant-label">Contenance</label>
                        <div class="variant-options">
                            <?php foreach ($prod['variants'] as $index => $variant): ?>
                                <button class="variant-btn <?= $index === 1 ? 'active' : '' ?>" 
                                        data-variant-id="<?= $variant['id'] ?>" 
                                        data-price="<?= $variant['price'] ?>">
                                    <?= $variant['volume'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Quantity & Add to Cart -->
                <div class="product-actions-detail">
                    <div class="quantity-selector">
                        <button class="qty-btn qty-minus" type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="qty-input" value="1" min="1" max="<?= $prod['stock'] ?? 99 ?>" id="product-qty">
                        <button class="qty-btn qty-plus" type="button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    
                    <button class="btn btn-primary btn-lg btn-add-to-cart" 
                            data-product-id="<?= $prod['id'] ?>"
                            <?= ($prod['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                        <i class="fas fa-shopping-bag"></i>
                        <span><?= ($prod['stock'] ?? 0) > 0 ? 'Ajouter au panier' : 'Rupture de stock' ?></span>
                    </button>
                    
                    <button class="btn btn-outline btn-wishlist-detail" data-product-id="<?= $prod['id'] ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                
                <!-- Trust Badges -->
                <div class="product-trust-badges">
                    <div class="trust-item">
                        <i class="fas fa-truck"></i>
                        <span>Livraison 24-48h<br><small>Partout dans le pays</small></span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Paiement sécurisé<br><small>100% sécurisé</small></span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-undo"></i>
                        <span>Retour facile<br><small>Sous 7 jours</small></span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-certificate"></i>
                        <span>Produits authentiques<br><small>Qualité garantie</small></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs" data-aos="fade-up">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="pyramid">Pyramide Olfactive</button>
                <button class="tab-btn" data-tab="reviews">Avis (<?= $prod['reviews_count'] ?? 0 ?>)</button>
            </div>
            
            <div class="tabs-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="tab-description">
                    <div class="description-content">
                        <?= $prod['description'] ?? '' ?>
                        
                        <div class="product-specs">
                            <h4>Caractéristiques</h4>
                            <table class="specs-table">
                                <tr>
                                    <td>Concentration</td>
                                    <td><?= htmlspecialchars($prod['concentration'] ?? 'Eau de Parfum') ?></td>
                                </tr>
                                <tr>
                                    <td>Genre</td>
                                    <td><?= htmlspecialchars($prod['genre'] ?? 'Unisexe') ?></td>
                                </tr>
                                <tr>
                                    <td>Famille olfactive</td>
                                    <td>Oriental Boisé</td>
                                </tr>
                                <tr>
                                    <td>Tenue</td>
                                    <td>8-12 heures</td>
                                </tr>
                                <tr>
                                    <td>Sillage</td>
                                    <td>Modéré à intense</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Olfactory Pyramid Tab -->
                <div class="tab-pane" id="tab-pyramid">
                    <?php if (!empty($prod['notes'])): ?>
                        <div class="olfactory-pyramid">
                            <div class="pyramid-visual">
                                <!-- Note de tête -->
                                <div class="pyramid-level level-top">
                                    <div class="level-icon">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                    <div class="level-content">
                                        <h4>Notes de Tête</h4>
                                        <p class="level-timing">0 - 15 minutes</p>
                                        <div class="notes-tags">
                                            <?php foreach ($prod['notes']['top'] as $note): ?>
                                                <span class="note-tag"><?= htmlspecialchars($note) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="level-description">Les premières impressions, vives et fraîches, qui s'évaporent rapidement.</p>
                                    </div>
                                </div>
                                
                                <!-- Note de cœur -->
                                <div class="pyramid-level level-heart">
                                    <div class="level-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="level-content">
                                        <h4>Notes de Cœur</h4>
                                        <p class="level-timing">15 min - 4 heures</p>
                                        <div class="notes-tags">
                                            <?php foreach ($prod['notes']['heart'] as $note): ?>
                                                <span class="note-tag"><?= htmlspecialchars($note) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="level-description">L'âme du parfum, qui se révèle une fois les notes de tête dissipées.</p>
                                    </div>
                                </div>
                                
                                <!-- Note de fond -->
                                <div class="pyramid-level level-base">
                                    <div class="level-icon">
                                        <i class="fas fa-gem"></i>
                                    </div>
                                    <div class="level-content">
                                        <h4>Notes de Fond</h4>
                                        <p class="level-timing">4+ heures</p>
                                        <div class="notes-tags">
                                            <?php foreach ($prod['notes']['base'] as $note): ?>
                                                <span class="note-tag"><?= htmlspecialchars($note) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="level-description">La signature durable qui reste sur la peau pendant des heures.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Les notes olfactives ne sont pas encore disponibles pour ce produit.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane" id="tab-reviews">
                    <div class="reviews-section">
                        <div class="reviews-summary">
                            <div class="rating-big">
                                <span class="rating-number"><?= number_format($prod['rating'] ?? 0, 1) ?></span>
                                <div class="rating-stars">
                                    <?php 
                                    $rating = $prod['rating'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <?php if ($i <= floor($rating)): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i - 0.5 <= $rating): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-total">Basé sur <?= $prod['reviews_count'] ?? 0 ?> avis</span>
                            </div>
                            
                            <div class="rating-bars">
                                <?php
                                $ratingDistribution = [
                                    5 => 70,
                                    4 => 20,
                                    3 => 7,
                                    2 => 2,
                                    1 => 1
                                ];
                                foreach ($ratingDistribution as $stars => $percent):
                                ?>
                                    <div class="rating-bar-item">
                                        <span class="bar-label"><?= $stars ?> <i class="fas fa-star"></i></span>
                                        <div class="bar-track">
                                            <div class="bar-fill" style="width: <?= $percent ?>%"></div>
                                        </div>
                                        <span class="bar-percent"><?= $percent ?>%</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" id="btn-write-review">
                            <i class="fas fa-pen"></i>
                            <span>Écrire un avis</span>
                        </button>
                        
                        <!-- Sample Reviews -->
                        <div class="reviews-list">
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <span>AK</span>
                                        </div>
                                        <div class="reviewer-details">
                                            <h5 class="reviewer-name">Awa Koné</h5>
                                            <span class="review-date">Il y a 2 semaines</span>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p>Un parfum absolument exceptionnel ! La tenue est incroyable et je reçois des compliments à chaque fois que je le porte. Le mélange d'oud et de rose est parfaitement équilibré.</p>
                                </div>
                                <div class="review-footer">
                                    <span class="verified-badge"><i class="fas fa-check-circle"></i> Achat vérifié</span>
                                </div>
                            </div>
                            
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <span>YD</span>
                                        </div>
                                        <div class="reviewer-details">
                                            <h5 class="reviewer-name">Yacine Diallo</h5>
                                            <span class="review-date">Il y a 1 mois</span>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= 4): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p>Très bon parfum, élégant et raffiné. La projection pourrait être un peu plus forte mais la qualité est au rendez-vous. Je recommande !</p>
                                </div>
                                <div class="review-footer">
                                    <span class="verified-badge"><i class="fas fa-check-circle"></i> Achat vérifié</span>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline btn-load-more">
                            Voir plus d'avis
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<section class="related-products section-padding bg-dark-alt">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Vous aimerez aussi</h2>
        </div>
        
        <div class="products-grid products-grid-4" data-aos="fade-up">
            <?php
            $related = [
                ['id' => 3, 'name' => 'Ambre Éternel', 'slug' => 'ambre-eternel', 'price' => 85000, 'image' => 'assets/images/products/ambre-eternel.jpg', 'rating' => 4.7, 'reviews_count' => 42, 'category_name' => 'Extrait de Parfum'],
                ['id' => 4, 'name' => 'Velvet Rose', 'slug' => 'velvet-rose', 'price' => 85000, 'image' => 'assets/images/products/velvet-rose.jpg', 'rating' => 4.5, 'reviews_count' => 31, 'category_name' => 'Eau de Parfum'],
                ['id' => 6, 'name' => 'Bois Précieux', 'slug' => 'bois-precieux', 'price' => 90000, 'image' => 'assets/images/products/bois-precieux.jpg', 'rating' => 4.6, 'reviews_count' => 29, 'category_name' => 'Extrait de Parfum'],
                ['id' => 8, 'name' => 'Lumière Blanche', 'slug' => 'lumiere-blanche', 'price' => 100000, 'image' => 'assets/images/products/lumiere-blanche.jpg', 'rating' => 4.8, 'reviews_count' => 33, 'category_name' => 'Eau de Parfum'],
            ];
            foreach ($related as $prod):
            ?>
                <div class="product-card">
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
                        <span class="product-category"><?= $prod['category_name'] ?></span>
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

<!-- Review Modal -->
<div class="modal" id="review-modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Écrire un avis</h3>
            <button class="modal-close"><i class="fas fa-times"></i></button>
        </div>
        <form class="review-form" id="review-form">
            <input type="hidden" name="product_id" value="<?= $prod['id'] ?? 1 ?>">
            
            <div class="form-group">
                <label>Votre note</label>
                <div class="rating-input">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>">
                        <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="review-title">Titre de l'avis</label>
                <input type="text" id="review-title" name="title" placeholder="Résumez votre expérience" required>
            </div>
            
            <div class="form-group">
                <label for="review-content">Votre avis</label>
                <textarea id="review-content" name="content" rows="5" placeholder="Partagez votre expérience avec ce produit..." required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline modal-close">Annuler</button>
                <button type="submit" class="btn btn-primary">Publier l'avis</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Gallery
    const galleryThumbs = new Swiper('#gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        watchSlidesProgress: true,
    });
    
    const galleryMain = new Swiper('#gallery-main', {
        spaceBetween: 10,
        thumbs: {
            swiper: galleryThumbs,
        },
    });
    
    // Variant Selection
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const price = this.dataset.price;
            document.querySelector('.product-price-detail .price-current').textContent = formatPrice(price);
        });
    });
    
    // Quantity Selector
    const qtyInput = document.getElementById('product-qty');
    document.querySelector('.qty-minus').addEventListener('click', () => {
        if (qtyInput.value > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
    });
    document.querySelector('.qty-plus').addEventListener('click', () => {
        if (qtyInput.value < qtyInput.max) qtyInput.value = parseInt(qtyInput.value) + 1;
    });
    
    // Tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });
    
    // Review Modal
    const reviewModal = document.getElementById('review-modal');
    document.getElementById('btn-write-review').addEventListener('click', () => {
        reviewModal.classList.add('active');
    });
    reviewModal.querySelectorAll('.modal-close, .modal-overlay').forEach(el => {
        el.addEventListener('click', () => reviewModal.classList.remove('active'));
    });
    
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
