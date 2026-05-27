<?php
/**
 * TATAVERNIS - Boutique / Catalogue Produits
 */

require_once 'config/config.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

$product = new Product();
$category = new Category();

// Paramètres de filtrage
$categorySlug = $_GET['categorie'] ?? null;
$genre = $_GET['genre'] ?? null;
$priceMin = isset($_GET['prix_min']) ? (int)$_GET['prix_min'] : null;
$priceMax = isset($_GET['prix_max']) ? (int)$_GET['prix_max'] : null;
$sort = $_GET['tri'] ?? 'populaire';
$search = $_GET['q'] ?? null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;

// Construire les filtres
$filters = [];
if ($categorySlug) $filters['category_slug'] = $categorySlug;
if ($genre) $filters['genre'] = $genre;
if ($priceMin) $filters['price_min'] = $priceMin;
if ($priceMax) $filters['price_max'] = $priceMax;
if ($search) $filters['search'] = $search;

// Récupérer les produits
$result = $product->getFiltered($filters, $sort, $page, $perPage);
$products = $result['products'];
$totalProducts = $result['total'];
$totalPages = ceil($totalProducts / $perPage);

// Récupérer les catégories pour le filtre
$categories = $category->getAll();

// Prix min/max pour le slider
$priceRange = $product->getPriceRange();

$pageTitle = $categorySlug ? ucfirst(str_replace('-', ' ', $categorySlug)) : "Nos Parfums";
$pageDescription = "Découvrez notre collection de parfums d'exception. Des créations uniques pour homme, femme et maison.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Accueil</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span>Parfums</span>
            <?php if ($categorySlug): ?>
                <span class="separator"><i class="fas fa-chevron-right"></i></span>
                <span><?= htmlspecialchars(ucfirst(str_replace('-', ' ', $categorySlug))) ?></span>
            <?php endif; ?>
        </nav>
        <h1 class="page-title">Nos Parfums</h1>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section section-padding">
    <div class="container">
        <!-- Filters Bar -->
        <div class="filters-bar">
            <div class="filters-left">
                <span class="filter-label">Filtrer par :</span>
                
                <!-- Category Filter -->
                <div class="filter-dropdown">
                    <button class="filter-btn" data-dropdown="categories">
                        <span>Catégories</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="categories-dropdown">
                        <a href="boutique.php" class="dropdown-item <?= !$categorySlug ? 'active' : '' ?>">Toutes</a>
                        <a href="boutique.php?categorie=parfums-homme" class="dropdown-item <?= $categorySlug === 'parfums-homme' ? 'active' : '' ?>">Parfums Homme</a>
                        <a href="boutique.php?categorie=parfums-femme" class="dropdown-item <?= $categorySlug === 'parfums-femme' ? 'active' : '' ?>">Parfums Femme</a>
                        <a href="boutique.php?categorie=senteurs-unisexes" class="dropdown-item <?= $categorySlug === 'senteurs-unisexes' ? 'active' : '' ?>">Senteurs Unisexes</a>
                        <a href="boutique.php?categorie=senteurs-maison" class="dropdown-item <?= $categorySlug === 'senteurs-maison' ? 'active' : '' ?>">Senteurs Maison</a>
                        <a href="boutique.php?categorie=coffrets" class="dropdown-item <?= $categorySlug === 'coffrets' ? 'active' : '' ?>">Coffrets</a>
                    </div>
                </div>
                
                <!-- Genre Filter -->
                <div class="filter-dropdown">
                    <button class="filter-btn" data-dropdown="genres">
                        <span>Genres</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="genres-dropdown">
                        <a href="<?= updateQueryString(['genre' => null]) ?>" class="dropdown-item <?= !$genre ? 'active' : '' ?>">Tous</a>
                        <a href="<?= updateQueryString(['genre' => 'homme']) ?>" class="dropdown-item <?= $genre === 'homme' ? 'active' : '' ?>">Homme</a>
                        <a href="<?= updateQueryString(['genre' => 'femme']) ?>" class="dropdown-item <?= $genre === 'femme' ? 'active' : '' ?>">Femme</a>
                        <a href="<?= updateQueryString(['genre' => 'unisexe']) ?>" class="dropdown-item <?= $genre === 'unisexe' ? 'active' : '' ?>">Unisexe</a>
                    </div>
                </div>
                
                <!-- Price Filter -->
                <div class="filter-dropdown">
                    <button class="filter-btn" data-dropdown="price">
                        <span>Prix</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-price" id="price-dropdown">
                        <div class="price-range-slider">
                            <div class="price-inputs">
                                <div class="price-input">
                                    <label>Min</label>
                                    <input type="number" id="price-min" value="<?= $priceMin ?? 0 ?>" min="0" max="500000">
                                </div>
                                <span class="price-separator">-</span>
                                <div class="price-input">
                                    <label>Max</label>
                                    <input type="number" id="price-max" value="<?= $priceMax ?? 500000 ?>" min="0" max="500000">
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary" id="apply-price-filter">Appliquer</button>
                        </div>
                    </div>
                </div>
                
                <button class="filter-btn btn-filter-mobile" id="open-filters-mobile">
                    <i class="fas fa-sliders-h"></i>
                    <span>Filtres</span>
                </button>
            </div>
            
            <div class="filters-right">
                <!-- Sort -->
                <div class="filter-dropdown">
                    <button class="filter-btn" data-dropdown="sort">
                        <span>Trier par : </span>
                        <strong>
                            <?php
                            $sortLabels = [
                                'populaire' => 'Popularité',
                                'recent' => 'Nouveautés',
                                'prix-asc' => 'Prix croissant',
                                'prix-desc' => 'Prix décroissant',
                                'note' => 'Meilleures notes'
                            ];
                            echo $sortLabels[$sort] ?? 'Popularité';
                            ?>
                        </strong>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-right" id="sort-dropdown">
                        <a href="<?= updateQueryString(['tri' => 'populaire']) ?>" class="dropdown-item <?= $sort === 'populaire' ? 'active' : '' ?>">Popularité</a>
                        <a href="<?= updateQueryString(['tri' => 'recent']) ?>" class="dropdown-item <?= $sort === 'recent' ? 'active' : '' ?>">Nouveautés</a>
                        <a href="<?= updateQueryString(['tri' => 'prix-asc']) ?>" class="dropdown-item <?= $sort === 'prix-asc' ? 'active' : '' ?>">Prix croissant</a>
                        <a href="<?= updateQueryString(['tri' => 'prix-desc']) ?>" class="dropdown-item <?= $sort === 'prix-desc' ? 'active' : '' ?>">Prix décroissant</a>
                        <a href="<?= updateQueryString(['tri' => 'note']) ?>" class="dropdown-item <?= $sort === 'note' ? 'active' : '' ?>">Meilleures notes</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Filters -->
        <?php if ($categorySlug || $genre || $priceMin || $priceMax || $search): ?>
            <div class="active-filters">
                <?php if ($search): ?>
                    <span class="filter-tag">
                        Recherche: "<?= htmlspecialchars($search) ?>"
                        <a href="<?= updateQueryString(['q' => null]) ?>"><i class="fas fa-times"></i></a>
                    </span>
                <?php endif; ?>
                <?php if ($categorySlug): ?>
                    <span class="filter-tag">
                        <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $categorySlug))) ?>
                        <a href="<?= updateQueryString(['categorie' => null]) ?>"><i class="fas fa-times"></i></a>
                    </span>
                <?php endif; ?>
                <?php if ($genre): ?>
                    <span class="filter-tag">
                        <?= htmlspecialchars(ucfirst($genre)) ?>
                        <a href="<?= updateQueryString(['genre' => null]) ?>"><i class="fas fa-times"></i></a>
                    </span>
                <?php endif; ?>
                <?php if ($priceMin || $priceMax): ?>
                    <span class="filter-tag">
                        Prix: <?= formatPrice($priceMin ?? 0) ?> - <?= formatPrice($priceMax ?? 500000) ?>
                        <a href="<?= updateQueryString(['prix_min' => null, 'prix_max' => null]) ?>"><i class="fas fa-times"></i></a>
                    </span>
                <?php endif; ?>
                <a href="boutique.php" class="clear-all-filters">Effacer tout</a>
            </div>
        <?php endif; ?>
        
        <!-- Results Count -->
        <div class="results-count">
            <p><?= $totalProducts ?> produit<?= $totalProducts > 1 ? 's' : '' ?> trouvé<?= $totalProducts > 1 ? 's' : '' ?></p>
        </div>
        
        <!-- Products Grid -->
        <div class="products-grid products-grid-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $prod): ?>
                    <div class="product-card" data-aos="fade-up">
                        <?php if (!empty($prod['is_new'])): ?>
                            <span class="product-badge badge-new">Nouveau</span>
                        <?php elseif (!empty($prod['sale_price'])): ?>
                            <span class="product-badge badge-sale">-<?= round((1 - $prod['sale_price'] / $prod['price']) * 100) ?>%</span>
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
                            <img src="<?= $prod['image'] ?? 'assets/images/products/placeholder.jpg' ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                        </a>
                        <div class="product-info">
                            <span class="product-category"><?= htmlspecialchars($prod['category_name'] ?? 'Parfum') ?></span>
                            <h3 class="product-name">
                                <a href="produit.php?slug=<?= $prod['slug'] ?>"><?= htmlspecialchars($prod['name']) ?></a>
                            </h3>
                            <div class="product-rating">
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
                                <span class="rating-count">(<?= $prod['reviews_count'] ?? 0 ?>)</span>
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
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Demo Products -->
                <?php
                $demoProducts = [
                    ['id' => 1, 'name' => 'Oud Royal', 'slug' => 'oud-royal', 'price' => 120000, 'sale_price' => null, 'image' => 'assets/images/products/oud-royal.jpg', 'rating' => 4.8, 'reviews_count' => 48, 'is_new' => false, 'category_name' => 'Extrait de Parfum'],
                    ['id' => 2, 'name' => 'Vanille Noire', 'slug' => 'vanille-noire', 'price' => 80000, 'sale_price' => null, 'image' => 'assets/images/products/vanille-noire.jpg', 'rating' => 4.6, 'reviews_count' => 36, 'is_new' => true, 'category_name' => 'Eau de Parfum'],
                    ['id' => 3, 'name' => 'Ambre Éternel', 'slug' => 'ambre-eternel', 'price' => 85000, 'sale_price' => null, 'image' => 'assets/images/products/ambre-eternel.jpg', 'rating' => 4.7, 'reviews_count' => 42, 'is_new' => false, 'category_name' => 'Extrait de Parfum'],
                    ['id' => 4, 'name' => 'Velvet Rose', 'slug' => 'velvet-rose', 'price' => 85000, 'sale_price' => null, 'image' => 'assets/images/products/velvet-rose.jpg', 'rating' => 4.5, 'reviews_count' => 31, 'is_new' => true, 'category_name' => 'Eau de Parfum'],
                    ['id' => 5, 'name' => 'Citrus Gold', 'slug' => 'citrus-gold', 'price' => 75000, 'sale_price' => null, 'image' => 'assets/images/products/citrus-gold.jpg', 'rating' => 4.4, 'reviews_count' => 27, 'is_new' => false, 'category_name' => 'Eau de Parfum'],
                    ['id' => 6, 'name' => 'Bois Précieux', 'slug' => 'bois-precieux', 'price' => 90000, 'sale_price' => null, 'image' => 'assets/images/products/bois-precieux.jpg', 'rating' => 4.6, 'reviews_count' => 29, 'is_new' => true, 'category_name' => 'Extrait de Parfum'],
                    ['id' => 7, 'name' => 'Musc Blanc', 'slug' => 'musc-blanc', 'price' => 70000, 'sale_price' => null, 'image' => 'assets/images/products/musc-blanc.jpg', 'rating' => 4.5, 'reviews_count' => 22, 'is_new' => false, 'category_name' => 'Eau de Parfum'],
                    ['id' => 8, 'name' => 'Lumière Blanche', 'slug' => 'lumiere-blanche', 'price' => 100000, 'sale_price' => null, 'image' => 'assets/images/products/lumiere-blanche.jpg', 'rating' => 4.8, 'reviews_count' => 33, 'is_new' => true, 'category_name' => 'Eau de Parfum'],
                ];
                foreach ($demoProducts as $prod):
                ?>
                    <div class="product-card" data-aos="fade-up">
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
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= updateQueryString(['page' => $page - 1]) ?>" class="page-link page-prev">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                if ($startPage > 1): ?>
                    <a href="<?= updateQueryString(['page' => 1]) ?>" class="page-link">1</a>
                    <?php if ($startPage > 2): ?>
                        <span class="page-dots">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="<?= updateQueryString(['page' => $i]) ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <span class="page-dots">...</span>
                    <?php endif; ?>
                    <a href="<?= updateQueryString(['page' => $totalPages]) ?>" class="page-link"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="<?= updateQueryString(['page' => $page + 1]) ?>" class="page-link page-next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</section>

<!-- Mobile Filters Sidebar -->
<div class="filters-sidebar-overlay" id="filters-overlay"></div>
<aside class="filters-sidebar" id="filters-sidebar">
    <div class="sidebar-header">
        <h3>Filtres</h3>
        <button class="close-sidebar" id="close-filters">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <!-- Categories -->
        <div class="filter-group">
            <h4 class="filter-group-title">Catégories</h4>
            <ul class="filter-list">
                <li><a href="boutique.php" class="<?= !$categorySlug ? 'active' : '' ?>">Toutes les catégories</a></li>
                <li><a href="boutique.php?categorie=parfums-homme" class="<?= $categorySlug === 'parfums-homme' ? 'active' : '' ?>">Parfums Homme</a></li>
                <li><a href="boutique.php?categorie=parfums-femme" class="<?= $categorySlug === 'parfums-femme' ? 'active' : '' ?>">Parfums Femme</a></li>
                <li><a href="boutique.php?categorie=senteurs-unisexes" class="<?= $categorySlug === 'senteurs-unisexes' ? 'active' : '' ?>">Senteurs Unisexes</a></li>
                <li><a href="boutique.php?categorie=senteurs-maison" class="<?= $categorySlug === 'senteurs-maison' ? 'active' : '' ?>">Senteurs Maison</a></li>
                <li><a href="boutique.php?categorie=coffrets" class="<?= $categorySlug === 'coffrets' ? 'active' : '' ?>">Coffrets</a></li>
            </ul>
        </div>
        
        <!-- Genre -->
        <div class="filter-group">
            <h4 class="filter-group-title">Genre</h4>
            <ul class="filter-list">
                <li><a href="<?= updateQueryString(['genre' => null]) ?>" class="<?= !$genre ? 'active' : '' ?>">Tous</a></li>
                <li><a href="<?= updateQueryString(['genre' => 'homme']) ?>" class="<?= $genre === 'homme' ? 'active' : '' ?>">Homme</a></li>
                <li><a href="<?= updateQueryString(['genre' => 'femme']) ?>" class="<?= $genre === 'femme' ? 'active' : '' ?>">Femme</a></li>
                <li><a href="<?= updateQueryString(['genre' => 'unisexe']) ?>" class="<?= $genre === 'unisexe' ? 'active' : '' ?>">Unisexe</a></li>
            </ul>
        </div>
        
        <!-- Price Range -->
        <div class="filter-group">
            <h4 class="filter-group-title">Prix</h4>
            <div class="price-range-inputs">
                <input type="number" id="mobile-price-min" placeholder="Min" value="<?= $priceMin ?? '' ?>">
                <span>-</span>
                <input type="number" id="mobile-price-max" placeholder="Max" value="<?= $priceMax ?? '' ?>">
            </div>
            <button class="btn btn-primary btn-block" id="apply-mobile-price">Appliquer</button>
        </div>
    </div>
</aside>

<?php include 'includes/footer.php'; ?>
