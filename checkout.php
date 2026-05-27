<?php
/**
 * TATAVERNIS - Checkout / Paiement
 */

require_once 'config/config.php';
require_once 'classes/Cart.php';
require_once 'classes/Auth.php';
require_once 'classes/Order.php';

$cart = new Cart();
$auth = new Auth();

// Vérifier si le panier n'est pas vide
$cartItems = $cart->getItems();
$cartTotal = $cart->getTotal();
$cartCount = $cart->getCount();

// Demo cart for display
$demoCartItems = [
    ['id' => 1, 'name' => 'Oud Royal', 'variant' => '100 ml', 'image' => 'assets/images/products/oud-royal.jpg', 'price' => 120000, 'quantity' => 1],
    ['id' => 2, 'name' => 'Vanille Noire', 'variant' => '100 ml', 'image' => 'assets/images/products/vanille-noire.jpg', 'price' => 80000, 'quantity' => 1],
    ['id' => 3, 'name' => 'Lumière Blanche', 'variant' => '100 ml', 'image' => 'assets/images/products/lumiere-blanche.jpg', 'price' => 100000, 'quantity' => 1],
];

$items = !empty($cartItems) ? $cartItems : $demoCartItems;
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shippingCost = $subtotal >= 50000 ? 0 : 5000;
$total = $subtotal + $shippingCost;

$isLoggedIn = $auth->isLoggedIn();
$user = $isLoggedIn ? $auth->getCurrentUser() : null;

$pageTitle = "Paiement";
$pageDescription = "Finalisez votre commande TATAVERNIS.";

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Accueil</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <a href="panier.php">Panier</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span>Paiement</span>
        </nav>
        <h1 class="page-title">Paiement</h1>
    </div>
</section>

<!-- Checkout Section -->
<section class="checkout-section section-padding">
    <div class="container">
        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label">Informations</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label">Livraison</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label">Paiement</span>
            </div>
            <div class="step" data-step="4">
                <span class="step-number">4</span>
                <span class="step-label">Confirmation</span>
            </div>
        </div>
        
        <div class="checkout-grid">
            <!-- Checkout Form -->
            <div class="checkout-form-container">
                <form id="checkout-form" class="checkout-form">
                    <!-- Step 1: Customer Information -->
                    <div class="checkout-step-content active" data-step="1">
                        <h2 class="step-title">Informations personnelles</h2>
                        
                        <?php if (!$isLoggedIn): ?>
                            <div class="guest-login-prompt">
                                <p>Vous avez déjà un compte ?</p>
                                <a href="connexion.php?redirect=checkout" class="btn btn-outline">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Se connecter</span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Prénom <span class="required">*</span></label>
                                <input type="text" id="first_name" name="first_name" required 
                                       value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Nom <span class="required">*</span></label>
                                <input type="text" id="last_name" name="last_name" required
                                       value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" required
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">Téléphone <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" required placeholder="+225 XX XX XX XX XX"
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <?php if (!$isLoggedIn): ?>
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="create_account" id="create_account">
                                    <span class="checkmark"></span>
                                    <span>Créer un compte pour suivre mes commandes</span>
                                </label>
                            </div>
                            
                            <div class="account-password-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="password">Mot de passe <span class="required">*</span></label>
                                    <input type="password" id="password" name="password" minlength="8">
                                    <small>Minimum 8 caractères</small>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="step-actions">
                            <a href="panier.php" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                <span>Retour au panier</span>
                            </a>
                            <button type="button" class="btn btn-primary btn-next-step" data-next="2">
                                <span>Continuer</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Shipping -->
                    <div class="checkout-step-content" data-step="2">
                        <h2 class="step-title">Adresse de livraison</h2>
                        
                        <?php if ($isLoggedIn && !empty($user['addresses'])): ?>
                            <div class="saved-addresses">
                                <h4>Adresses enregistrées</h4>
                                <!-- Saved addresses would be displayed here -->
                            </div>
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="new_address" id="new_address">
                                    <span class="checkmark"></span>
                                    <span>Utiliser une nouvelle adresse</span>
                                </label>
                            </div>
                        <?php endif; ?>
                        
                        <div class="address-form">
                            <div class="form-group">
                                <label for="address">Adresse <span class="required">*</span></label>
                                <input type="text" id="address" name="address" required placeholder="Numéro et nom de rue">
                            </div>
                            
                            <div class="form-group">
                                <label for="address2">Complément d'adresse</label>
                                <input type="text" id="address2" name="address2" placeholder="Appartement, étage, etc.">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">Ville <span class="required">*</span></label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="commune">Commune <span class="required">*</span></label>
                                    <select id="commune" name="commune" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="cocody">Cocody</option>
                                        <option value="plateau">Plateau</option>
                                        <option value="marcory">Marcory</option>
                                        <option value="treichville">Treichville</option>
                                        <option value="yopougon">Yopougon</option>
                                        <option value="abobo">Abobo</option>
                                        <option value="adjame">Adjamé</option>
                                        <option value="koumassi">Koumassi</option>
                                        <option value="port-bouet">Port-Bouët</option>
                                        <option value="bingerville">Bingerville</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="country">Pays <span class="required">*</span></label>
                                <select id="country" name="country" required>
                                    <option value="CI" selected>Côte d'Ivoire</option>
                                    <option value="SN">Sénégal</option>
                                    <option value="ML">Mali</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="GN">Guinée</option>
                                    <option value="BJ">Bénin</option>
                                    <option value="TG">Togo</option>
                                    <option value="CM">Cameroun</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="delivery_notes">Instructions de livraison</label>
                                <textarea id="delivery_notes" name="delivery_notes" rows="3" 
                                          placeholder="Instructions particulières pour le livreur..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Shipping Methods -->
                        <div class="shipping-methods">
                            <h4>Mode de livraison</h4>
                            
                            <div class="shipping-option">
                                <label class="radio-card">
                                    <input type="radio" name="shipping_method" value="standard" checked>
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-truck"></i>
                                                Livraison Standard
                                            </span>
                                            <span class="radio-price"><?= $subtotal >= 50000 ? 'Gratuit' : formatPrice(5000) ?></span>
                                        </span>
                                        <span class="radio-description">Livraison sous 3-5 jours ouvrés</span>
                                    </span>
                                </label>
                            </div>
                            
                            <div class="shipping-option">
                                <label class="radio-card">
                                    <input type="radio" name="shipping_method" value="express">
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-shipping-fast"></i>
                                                Livraison Express
                                            </span>
                                            <span class="radio-price"><?= formatPrice(10000) ?></span>
                                        </span>
                                        <span class="radio-description">Livraison sous 24-48h</span>
                                    </span>
                                </label>
                            </div>
                            
                            <div class="shipping-option">
                                <label class="radio-card">
                                    <input type="radio" name="shipping_method" value="pickup">
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-store"></i>
                                                Retrait en boutique
                                            </span>
                                            <span class="radio-price">Gratuit</span>
                                        </span>
                                        <span class="radio-description">Disponible sous 24h - Abidjan, Côte d'Ivoire</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="step-actions">
                            <button type="button" class="btn btn-outline btn-prev-step" data-prev="1">
                                <i class="fas fa-arrow-left"></i>
                                <span>Retour</span>
                            </button>
                            <button type="button" class="btn btn-primary btn-next-step" data-next="3">
                                <span>Continuer</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Payment -->
                    <div class="checkout-step-content" data-step="3">
                        <h2 class="step-title">Mode de paiement</h2>
                        
                        <div class="payment-methods-checkout">
                            <!-- Mobile Money -->
                            <div class="payment-method">
                                <label class="radio-card">
                                    <input type="radio" name="payment_method" value="mobile_money" checked>
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-mobile-alt"></i>
                                                Mobile Money
                                            </span>
                                        </span>
                                        <span class="radio-description">Orange Money, MTN Mobile Money, Wave, Moov Money</span>
                                        <div class="payment-logos">
                                            <img src="assets/images/payments/orange-money.svg" alt="Orange Money">
                                            <img src="assets/images/payments/mtn-money.svg" alt="MTN Money">
                                            <img src="assets/images/payments/wave.svg" alt="Wave">
                                            <img src="assets/images/payments/moov-money.svg" alt="Moov Money">
                                        </div>
                                    </span>
                                </label>
                                
                                <div class="payment-details" id="mobile-money-details">
                                    <div class="form-group">
                                        <label for="mobile_provider">Opérateur</label>
                                        <select id="mobile_provider" name="mobile_provider">
                                            <option value="orange">Orange Money</option>
                                            <option value="mtn">MTN Mobile Money</option>
                                            <option value="wave">Wave</option>
                                            <option value="moov">Moov Money</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_number">Numéro de téléphone</label>
                                        <input type="tel" id="mobile_number" name="mobile_number" placeholder="+225 XX XX XX XX XX">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Payment -->
                            <div class="payment-method">
                                <label class="radio-card">
                                    <input type="radio" name="payment_method" value="card">
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-credit-card"></i>
                                                Carte bancaire
                                            </span>
                                        </span>
                                        <span class="radio-description">Visa, Mastercard</span>
                                        <div class="payment-logos">
                                            <img src="assets/images/payments/visa.svg" alt="Visa">
                                            <img src="assets/images/payments/mastercard.svg" alt="Mastercard">
                                        </div>
                                    </span>
                                </label>
                                
                                <div class="payment-details" id="card-details" style="display: none;">
                                    <div class="form-group">
                                        <label for="card_number">Numéro de carte</label>
                                        <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="card_expiry">Date d'expiration</label>
                                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/AA">
                                        </div>
                                        <div class="form-group">
                                            <label for="card_cvv">CVV</label>
                                            <input type="text" id="card_cvv" name="card_cvv" placeholder="XXX" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="card_name">Nom sur la carte</label>
                                        <input type="text" id="card_name" name="card_name" placeholder="NOM PRÉNOM">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cash on Delivery -->
                            <div class="payment-method">
                                <label class="radio-card">
                                    <input type="radio" name="payment_method" value="cod">
                                    <span class="radio-content">
                                        <span class="radio-header">
                                            <span class="radio-title">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Paiement à la livraison
                                            </span>
                                        </span>
                                        <span class="radio-description">Payez en espèces à la réception de votre commande</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="checkout-terms">
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="accept_terms" id="accept_terms" required>
                                    <span class="checkmark"></span>
                                    <span>J'accepte les <a href="conditions-generales.php" target="_blank">conditions générales de vente</a> et la <a href="politique-confidentialite.php" target="_blank">politique de confidentialité</a> <span class="required">*</span></span>
                                </label>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="newsletter" id="newsletter">
                                    <span class="checkmark"></span>
                                    <span>Je souhaite recevoir les offres et nouveautés TATAVERNIS par email</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="step-actions">
                            <button type="button" class="btn btn-outline btn-prev-step" data-prev="2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Retour</span>
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-place-order">
                                <i class="fas fa-lock"></i>
                                <span>Confirmer la commande</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 4: Confirmation -->
                    <div class="checkout-step-content" data-step="4">
                        <div class="order-confirmation">
                            <div class="confirmation-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2 class="confirmation-title">Merci pour votre commande !</h2>
                            <p class="confirmation-message">
                                Votre commande a été enregistrée avec succès. Vous recevrez un email de confirmation à l'adresse indiquée.
                            </p>
                            <div class="order-number">
                                <span>Numéro de commande :</span>
                                <strong id="order-number">CMD-2024-XXXXX</strong>
                            </div>
                            <div class="confirmation-actions">
                                <a href="compte/commandes.php" class="btn btn-primary">
                                    <span>Suivre ma commande</span>
                                </a>
                                <a href="boutique.php" class="btn btn-outline">
                                    <span>Continuer mes achats</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="checkout-summary">
                <div class="summary-card">
                    <h3 class="summary-title">Récapitulatif de commande</h3>
                    
                    <div class="summary-items">
                        <?php foreach ($items as $item): ?>
                            <div class="summary-item">
                                <div class="item-image">
                                    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <span class="item-qty"><?= $item['quantity'] ?></span>
                                </div>
                                <div class="item-details">
                                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                                    <span class="item-variant"><?= htmlspecialchars($item['variant']) ?></span>
                                </div>
                                <div class="item-price">
                                    <?= formatPrice($item['price'] * $item['quantity']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Sous-total</span>
                            <span><?= formatPrice($subtotal) ?></span>
                        </div>
                        <div class="total-row" id="shipping-row">
                            <span>Livraison</span>
                            <span id="checkout-shipping"><?= $shippingCost > 0 ? formatPrice($shippingCost) : 'Gratuit' ?></span>
                        </div>
                        <div class="total-row discount-row" style="display: none;">
                            <span>Réduction</span>
                            <span id="checkout-discount">-0 FCFA</span>
                        </div>
                        <div class="total-row total-final">
                            <span>Total</span>
                            <span id="checkout-total"><?= formatPrice($total) ?></span>
                        </div>
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="summary-promo">
                        <div class="promo-input-group">
                            <input type="text" id="checkout-promo" placeholder="Code promo">
                            <button type="button" class="btn btn-sm" id="apply-checkout-promo">Appliquer</button>
                        </div>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="summary-trust">
                        <div class="trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Paiement 100% sécurisé</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-lock"></i>
                            <span>Données cryptées SSL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Step navigation
    const steps = document.querySelectorAll('.checkout-step-content');
    const stepIndicators = document.querySelectorAll('.checkout-steps .step');
    
    document.querySelectorAll('.btn-next-step').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStep = parseInt(this.closest('.checkout-step-content').dataset.step);
            const nextStep = parseInt(this.dataset.next);
            
            if (validateStep(currentStep)) {
                goToStep(nextStep);
            }
        });
    });
    
    document.querySelectorAll('.btn-prev-step').forEach(btn => {
        btn.addEventListener('click', function() {
            const prevStep = parseInt(this.dataset.prev);
            goToStep(prevStep);
        });
    });
    
    function goToStep(stepNum) {
        // Update content
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === stepNum) {
                step.classList.add('active');
            }
        });
        
        // Update indicators
        stepIndicators.forEach(indicator => {
            const indicatorStep = parseInt(indicator.dataset.step);
            indicator.classList.remove('active', 'completed');
            if (indicatorStep < stepNum) {
                indicator.classList.add('completed');
            } else if (indicatorStep === stepNum) {
                indicator.classList.add('active');
            }
        });
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function validateStep(stepNum) {
        const step = document.querySelector(`.checkout-step-content[data-step="${stepNum}"]`);
        const requiredFields = step.querySelectorAll('input[required], select[required]');
        let valid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                valid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!valid) {
            alert('Veuillez remplir tous les champs obligatoires.');
        }
        
        return valid;
    }
    
    // Create account toggle
    const createAccountCheckbox = document.getElementById('create_account');
    const passwordFields = document.querySelector('.account-password-fields');
    
    if (createAccountCheckbox && passwordFields) {
        createAccountCheckbox.addEventListener('change', function() {
            passwordFields.style.display = this.checked ? 'block' : 'none';
            const passwordInput = passwordFields.querySelector('input');
            if (passwordInput) {
                passwordInput.required = this.checked;
            }
        });
    }
    
    // Payment method toggle
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            document.querySelectorAll('.payment-details').forEach(detail => {
                detail.style.display = 'none';
            });
            
            if (this.value === 'mobile_money') {
                document.getElementById('mobile-money-details').style.display = 'block';
            } else if (this.value === 'card') {
                document.getElementById('card-details').style.display = 'block';
            }
        });
    });
    
    // Shipping method change
    const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
    shippingMethods.forEach(method => {
        method.addEventListener('change', function() {
            updateOrderSummary();
        });
    });
    
    function updateOrderSummary() {
        const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
        let shippingCost = 0;
        
        if (shippingMethod === 'standard') {
            shippingCost = <?= $subtotal >= 50000 ? 0 : 5000 ?>;
        } else if (shippingMethod === 'express') {
            shippingCost = 10000;
        }
        
        const subtotal = <?= $subtotal ?>;
        const total = subtotal + shippingCost;
        
        document.getElementById('checkout-shipping').textContent = shippingCost > 0 ? formatPrice(shippingCost) : 'Gratuit';
        document.getElementById('checkout-total').textContent = formatPrice(total);
    }
    
    // Form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const termsAccepted = document.getElementById('accept_terms').checked;
        if (!termsAccepted) {
            alert('Veuillez accepter les conditions générales de vente.');
            return;
        }
        
        // Simulate order placement
        const submitBtn = document.getElementById('btn-place-order');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Traitement en cours...</span>';
        
        setTimeout(function() {
            // Generate order number
            const orderNumber = 'CMD-' + new Date().getFullYear() + '-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            document.getElementById('order-number').textContent = orderNumber;
            
            // Go to confirmation step
            goToStep(4);
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> <span>Confirmer la commande</span>';
        }, 2000);
    });
    
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
