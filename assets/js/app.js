/**
 * TATAVERNIS - JavaScript principal
 * Maison de Parfumerie Premium
 */

// Configuration globale
const CONFIG = {
    apiUrl: '/api',
    currency: 'FCFA'
};

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// =====================================================
// Utility Functions
// =====================================================

/**
 * Format price
 */
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price) + ' ' + CONFIG.currency;
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Fetch with CSRF
 */
async function fetchWithCsrf(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    const mergedOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    };

    const response = await fetch(url, mergedOptions);
    return response.json();
}

// =====================================================
// Mobile Menu
// =====================================================

const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const mobileMenuClose = document.getElementById('mobile-menu-close');
const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
const mobileMenuPanel = document.getElementById('mobile-menu-panel');

function openMobileMenu() {
    mobileMenu.classList.remove('hidden');
    setTimeout(() => {
        mobileMenuPanel.classList.remove('translate-x-full');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    mobileMenuPanel.classList.add('translate-x-full');
    setTimeout(() => {
        mobileMenu.classList.add('hidden');
    }, 300);
    document.body.style.overflow = '';
}

mobileMenuToggle?.addEventListener('click', openMobileMenu);
mobileMenuClose?.addEventListener('click', closeMobileMenu);
mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

// =====================================================
// Search Modal
// =====================================================

const searchToggle = document.getElementById('search-toggle');
const searchModal = document.getElementById('search-modal');
const searchModalClose = document.getElementById('search-modal-close');
const searchModalOverlay = document.getElementById('search-modal-overlay');
const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

function openSearchModal() {
    searchModal.classList.remove('hidden');
    setTimeout(() => {
        searchInput?.focus();
    }, 100);
    document.body.style.overflow = 'hidden';
}

function closeSearchModal() {
    searchModal.classList.add('hidden');
    document.body.style.overflow = '';
    if (searchInput) searchInput.value = '';
    if (searchResults) {
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
    }
}

searchToggle?.addEventListener('click', openSearchModal);
searchModalClose?.addEventListener('click', closeSearchModal);
searchModalOverlay?.addEventListener('click', closeSearchModal);

// Escape key closes modals
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeSearchModal();
        closeMobileMenu();
    }
});

// Live Search
const performSearch = debounce(async (query) => {
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        return;
    }

    try {
        const data = await fetchWithCsrf(`${CONFIG.apiUrl}/search.php?q=${encodeURIComponent(query)}`);
        
        if (data.products && data.products.length > 0) {
            searchResults.innerHTML = data.products.map(product => `
                <a href="product.php?slug=${product.slug}" class="flex items-center gap-4 p-4 hover:bg-muted-light transition-colors">
                    <img src="${product.main_image || '/assets/images/placeholder.jpg'}" alt="${product.name}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">${product.name}</h4>
                        <p class="text-sm text-gray-400">${product.category_name || ''}</p>
                    </div>
                    <span class="text-gold font-semibold">${formatPrice(product.price)}</span>
                </a>
            `).join('');
            searchResults.classList.remove('hidden');
        } else {
            searchResults.innerHTML = '<p class="p-4 text-gray-400 text-center">Aucun produit trouvé</p>';
            searchResults.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Search error:', error);
    }
}, 300);

searchInput?.addEventListener('input', (e) => {
    performSearch(e.target.value.trim());
});

// =====================================================
// Cart Functions
// =====================================================

/**
 * Add to cart
 */
async function addToCart(productId, quantity = 1, size = null) {
    try {
        const data = await fetchWithCsrf(`${CONFIG.apiUrl}/add-to-cart.php`, {
            method: 'POST',
            body: JSON.stringify({ product_id: productId, quantity, size })
        });

        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Produit ajouté au panier', 'success');
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }
    } catch (error) {
        showNotification('Une erreur est survenue', 'error');
    }
}

/**
 * Update cart quantity
 */
async function updateCartQuantity(cartId, quantity) {
    try {
        const data = await fetchWithCsrf(`${CONFIG.apiUrl}/update-cart.php`, {
            method: 'POST',
            body: JSON.stringify({ cart_id: cartId, quantity })
        });

        if (data.success) {
            updateCartTotals(data.totals);
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }

        return data;
    } catch (error) {
        showNotification('Une erreur est survenue', 'error');
    }
}

/**
 * Remove from cart
 */
async function removeFromCart(cartId) {
    try {
        const data = await fetchWithCsrf(`${CONFIG.apiUrl}/remove-cart.php`, {
            method: 'POST',
            body: JSON.stringify({ cart_id: cartId })
        });

        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Article supprimé', 'success');
        }

        return data;
    } catch (error) {
        showNotification('Une erreur est survenue', 'error');
    }
}

/**
 * Update cart count in header
 */
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        if (count > 0) {
            cartCountElement.textContent = count;
            cartCountElement.classList.remove('hidden');
        } else {
            cartCountElement.classList.add('hidden');
        }
    }
}

/**
 * Update cart totals
 */
function updateCartTotals(totals) {
    const subtotalElement = document.getElementById('cart-subtotal');
    const shippingElement = document.getElementById('cart-shipping');
    const discountElement = document.getElementById('cart-discount');
    const totalElement = document.getElementById('cart-total');

    if (subtotalElement) subtotalElement.textContent = formatPrice(totals.subtotal);
    if (shippingElement) shippingElement.textContent = totals.shipping === 0 ? 'Gratuite' : formatPrice(totals.shipping);
    if (discountElement) discountElement.textContent = '-' + formatPrice(totals.discount);
    if (totalElement) totalElement.textContent = formatPrice(totals.total);
}

// =====================================================
// Wishlist Functions
// =====================================================

/**
 * Toggle wishlist
 */
async function toggleWishlist(productId, button) {
    try {
        const data = await fetchWithCsrf(`${CONFIG.apiUrl}/wishlist.php`, {
            method: 'POST',
            body: JSON.stringify({ product_id: productId })
        });

        if (data.success) {
            if (data.action === 'added') {
                button.classList.add('text-red-500');
                button.querySelector('svg')?.classList.add('fill-current');
                showNotification('Ajouté aux favoris', 'success');
            } else {
                button.classList.remove('text-red-500');
                button.querySelector('svg')?.classList.remove('fill-current');
                showNotification('Retiré des favoris', 'success');
            }
        }
    } catch (error) {
        showNotification('Une erreur est survenue', 'error');
    }
}

// =====================================================
// Notifications
// =====================================================

/**
 * Show notification
 */
function showNotification(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#2C2C2C',
        color: '#F8F5F0',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    const icons = {
        success: 'success',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };

    Toast.fire({
        icon: icons[type] || 'info',
        title: message
    });
}

// =====================================================
// Back to Top
// =====================================================

const backToTopButton = document.getElementById('back-to-top');

window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
        backToTopButton?.classList.remove('opacity-0', 'invisible');
        backToTopButton?.classList.add('opacity-100', 'visible');
    } else {
        backToTopButton?.classList.add('opacity-0', 'invisible');
        backToTopButton?.classList.remove('opacity-100', 'visible');
    }
});

backToTopButton?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// =====================================================
// Image Zoom
// =====================================================

function initImageZoom(container) {
    const image = container.querySelector('img');
    if (!image) return;

    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        image.style.transformOrigin = `${x}% ${y}%`;
        image.style.transform = 'scale(1.5)';
    });

    container.addEventListener('mouseleave', () => {
        image.style.transform = 'scale(1)';
    });
}

// Initialize zoom on product images
document.querySelectorAll('.zoom-container').forEach(initImageZoom);

// =====================================================
// Quantity Input
// =====================================================

document.querySelectorAll('.quantity-input').forEach(input => {
    const decreaseBtn = input.querySelector('[data-action="decrease"]');
    const increaseBtn = input.querySelector('[data-action="increase"]');
    const numberInput = input.querySelector('input[type="number"]');

    decreaseBtn?.addEventListener('click', () => {
        const currentValue = parseInt(numberInput.value) || 1;
        if (currentValue > 1) {
            numberInput.value = currentValue - 1;
            numberInput.dispatchEvent(new Event('change'));
        }
    });

    increaseBtn?.addEventListener('click', () => {
        const currentValue = parseInt(numberInput.value) || 1;
        const max = parseInt(numberInput.max) || 999;
        if (currentValue < max) {
            numberInput.value = currentValue + 1;
            numberInput.dispatchEvent(new Event('change'));
        }
    });
});

// =====================================================
// Newsletter Form
// =====================================================

const newsletterForm = document.getElementById('newsletter-form');
newsletterForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(newsletterForm);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetchWithCsrf(`${CONFIG.apiUrl}/newsletter.php`, {
            method: 'POST',
            body: JSON.stringify(data)
        });

        if (response.success) {
            showNotification('Inscription réussie!', 'success');
            newsletterForm.reset();
        } else {
            showNotification(response.message || 'Erreur', 'error');
        }
    } catch (error) {
        showNotification('Une erreur est survenue', 'error');
    }
});

// =====================================================
// Initialize Swipers
// =====================================================

// Hero Swiper
if (document.querySelector('.hero-swiper')) {
    new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
}

// Products Swiper
document.querySelectorAll('.products-swiper').forEach(el => {
    new Swiper(el, {
        slidesPerView: 1.2,
        spaceBetween: 16,
        breakpoints: {
            640: {
                slidesPerView: 2.2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 24
            }
        },
        navigation: {
            nextEl: el.querySelector('.swiper-button-next'),
            prevEl: el.querySelector('.swiper-button-prev')
        }
    });
});

// Collections Swiper
if (document.querySelector('.collections-swiper')) {
    new Swiper('.collections-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 16,
        breakpoints: {
            640: {
                slidesPerView: 2.5,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 24
            }
        }
    });
}

// Product Gallery Swiper
if (document.querySelector('.product-gallery-swiper')) {
    const thumbsSwiper = new Swiper('.product-thumbs-swiper', {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true
    });

    new Swiper('.product-gallery-swiper', {
        spaceBetween: 10,
        thumbs: {
            swiper: thumbsSwiper
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        }
    });
}

// =====================================================
// Lazy Loading
// =====================================================

if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}

// =====================================================
// Export functions for global use
// =====================================================

window.addToCart = addToCart;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.toggleWishlist = toggleWishlist;
window.showNotification = showNotification;
