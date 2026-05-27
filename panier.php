<?php
/**
 * TATAVERNIS - Panier
 */

require_once 'config/config.php';
require_once 'classes/Cart.php';

$cart = new Cart();
$cartItems = $cart->getItems();
$cartTotal = $cart->getTotal();
$cartCount = $cart->getCount();

$pageTitle = "Votre Panier";
$pageDescription = "Consultez et gérez votre panier d'achats TATAVERNIS.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Accueil</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span>Panier</span>
        </nav>
        <h1 class="page-title">Votre Panier</h1>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section section-padding">
    <div class="container">
        <?php if ($cartCount > 0 || true): // Demo mode ?>
            <div class="cart-grid">
                <!-- Cart Items -->
                <div class="cart-items">
                    <div class="cart-header">
                        <div class="cart-col col-product">Produit</div>
                        <div class="cart-col col-price">Prix</div>
                        <div class="cart-col col-quantity">Quantité</div>
                        <div class="cart-col col-total">Total</div>
                        <div class="cart-col col-remove"></div>
                    </div>
                    
                    <?php
                    // Demo cart items
                    $demoCartItems = [
                        [
                            'id' => 1,
                            'product_id' => 1,
                            'name' => 'Oud Royal',
                            'slug' => 'oud-royal',
                            'variant' => '100 ml',
                            'image' => 'assets/images/products/oud-royal.jpg',
                            'price' => 120000,
                            'quantity' => 1
                        ],
                        [
                            'id' => 2,
                            'product_id' => 2,
                            'name' => 'Vanille Noire',
                            'slug' => 'vanille-noire',
                            'variant' => '100 ml',
                            'image' => 'assets/images/products/vanille-noire.jpg',
                            'price' => 80000,
                            'quantity' => 1
                        ],
                        [
                            'id' => 3,
                            'product_id' => 8,
                            'name' => 'Lumière Blanche',
                            'slug' => 'lumiere-blanche',
                            'variant' => '100 ml',
                            'image' => 'assets/images/products/lumiere-blanche.jpg',
                            'price' => 100000,
                            'quantity' => 1
                        ]
                    ];
                    
                    $items = !empty($cartItems) ? $cartItems : $demoCartItems;
                    $subtotal = 0;
                    
                    foreach ($items as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemTotal;
                    ?>
                        <div class="cart-item" data-item-id="<?= $item['id'] ?>">
                            <div class="cart-col col-product">
                                <div class="item-product">
                                    <a href="produit.php?slug=<?= $item['slug'] ?>" class="item-image">
                                        <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </a>
                                    <div class="item-details">
                                        <h4 class="item-name">
                                            <a href="produit.php?slug=<?= $item['slug'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                        </h4>
                                        <span class="item-variant"><?= htmlspecialchars($item['variant']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-col col-price">
                                <span class="item-price"><?= formatPrice($item['price']) ?></span>
                            </div>
                            <div class="cart-col col-quantity">
                                <div class="quantity-selector">
                                    <button class="qty-btn qty-minus" type="button" data-item-id="<?= $item['id'] ?>">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="10" data-item-id="<?= $item['id'] ?>">
                                    <button class="qty-btn qty-plus" type="button" data-item-id="<?= $item['id'] ?>">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cart-col col-total">
                                <span class="item-total"><?= formatPrice($itemTotal) ?></span>
                            </div>
                            <div class="cart-col col-remove">
                                <button class="btn-remove-item" data-item-id="<?= $item['id'] ?>" title="Supprimer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 class="summary-title">Récapitulatif</h3>
                    
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span class="row-label">Sous-total</span>
                            <span class="row-value" id="cart-subtotal"><?= formatPrice($subtotal) ?></span>
                        </div>
                        <div class="summary-row">
                            <span class="row-label">Livraison</span>
                            <span class="row-value" id="cart-shipping">Calculé à l'étape suivante</span>
                        </div>
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="promo-code-form">
                        <label class="promo-label">Code promo</label>
                        <div class="promo-input-group">
                            <input type="text" id="promo-code" placeholder="Entrez votre code" class="promo-input">
                            <button type="button" class="btn btn-outline btn-apply-promo" id="apply-promo">
                                Appliquer
                            </button>
                        </div>
                        <div class="promo-message" id="promo-message"></div>
                    </div>
                    
                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-value" id="cart-total"><?= formatPrice($subtotal) ?></span>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary btn-lg btn-block btn-checkout">
                        <span>Passer la commande</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    
                    <a href="boutique.php" class="btn btn-outline btn-block btn-continue">
                        <i class="fas fa-arrow-left"></i>
                        <span>Continuer mes achats</span>
                    </a>
                    
                    <!-- Trust Badges -->
                    <div class="summary-trust">
                        <div class="trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Paiement sécurisé</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-truck"></i>
                            <span>Livraison rapide</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-undo"></i>
                            <span>Retour sous 7 jours</span>
                        </div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <span>Moyens de paiement acceptés :</span>
                        <div class="payment-icons">
                            <img src="assets/images/payments/visa.svg" alt="Visa">
                            <img src="assets/images/payments/mastercard.svg" alt="Mastercard">
                            <img src="assets/images/payments/orange-money.svg" alt="Orange Money">
                            <img src="assets/images/payments/mtn-money.svg" alt="MTN Money">
                            <img src="assets/images/payments/wave.svg" alt="Wave">
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty Cart -->
            <div class="cart-empty">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2>Votre panier est vide</h2>
                <p>Découvrez notre collection de parfums d'exception et laissez-vous séduire.</p>
                <a href="boutique.php" class="btn btn-primary btn-lg">
                    <span>Découvrir nos parfums</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- You May Also Like -->
<section class="related-products section-padding bg-dark-alt">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Vous aimerez aussi</h2>
        </div>
        
        <div class="products-grid products-grid-4" data-aos="fade-up">
            <?php
            $suggested = [
                ['id' => 3, 'name' => 'Ambre Éternel', 'slug' => 'ambre-eternel', 'price' => 85000, 'image' => 'assets/images/products/ambre-eternel.jpg', 'rating' => 4.7, 'reviews_count' => 42, 'category_name' => 'Extrait de Parfum'],
                ['id' => 4, 'name' => 'Velvet Rose', 'slug' => 'velvet-rose', 'price' => 85000, 'image' => 'assets/images/products/velvet-rose.jpg', 'rating' => 4.5, 'reviews_count' => 31, 'category_name' => 'Eau de Parfum'],
                ['id' => 5, 'name' => 'Citrus Gold', 'slug' => 'citrus-gold', 'price' => 75000, 'image' => 'assets/images/products/citrus-gold.jpg', 'rating' => 4.4, 'reviews_count' => 27, 'category_name' => 'Eau de Parfum'],
                ['id' => 6, 'name' => 'Bois Précieux', 'slug' => 'bois-precieux', 'price' => 90000, 'image' => 'assets/images/products/bois-precieux.jpg', 'rating' => 4.6, 'reviews_count' => 29, 'category_name' => 'Extrait de Parfum'],
            ];
            foreach ($suggested as $prod):
            ?>
                <div class="product-card">
                    <div class="product-actions">
                        <button class="action-btn btn-wishlist" data-product-id="<?= $prod['id'] ?>">
                            <i class="far fa-heart"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateCartItem(itemId, input.value);
            }
        });
    });
    
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
            if (input.value < 10) {
                input.value = parseInt(input.value) + 1;
                updateCartItem(itemId, input.value);
            }
        });
    });
    
    // Remove item
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            if (confirm('Voulez-vous supprimer cet article de votre panier ?')) {
                removeCartItem(itemId);
            }
        });
    });
    
    // Promo code
    document.getElementById('apply-promo').addEventListener('click', function() {
        const code = document.getElementById('promo-code').value.trim();
        if (code) {
            applyPromoCode(code);
        }
    });
    
    function updateCartItem(itemId, quantity) {
        fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update', item_id: itemId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartDisplay(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Update locally for demo
            updateCartDisplayLocally();
        });
    }
    
    function removeCartItem(itemId) {
        const item = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
        if (item) {
            item.style.opacity = '0';
            setTimeout(() => {
                item.remove();
                updateCartDisplayLocally();
            }, 300);
        }
    }
    
    function applyPromoCode(code) {
        const messageEl = document.getElementById('promo-message');
        
        // Demo promo codes
        const validCodes = {
            'BIENVENUE10': { type: 'percent', value: 10 },
            'NOEL2024': { type: 'percent', value: 15 },
            'LIVRAISON': { type: 'shipping', value: 0 }
        };
        
        if (validCodes[code.toUpperCase()]) {
            messageEl.innerHTML = '<span class="success"><i class="fas fa-check-circle"></i> Code promo appliqué !</span>';
            messageEl.style.color = '#4CAF50';
        } else {
            messageEl.innerHTML = '<span class="error"><i class="fas fa-times-circle"></i> Code promo invalide</span>';
            messageEl.style.color = '#f44336';
        }
    }
    
    function updateCartDisplayLocally() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseInt(item.querySelector('.item-price').textContent.replace(/[^\d]/g, ''));
            const qty = parseInt(item.querySelector('.qty-input').value);
            const total = price * qty;
            item.querySelector('.item-total').textContent = formatPrice(total);
            subtotal += total;
        });
        
        document.getElementById('cart-subtotal').textContent = formatPrice(subtotal);
        document.getElementById('cart-total').textContent = formatPrice(subtotal);
    }
    
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
