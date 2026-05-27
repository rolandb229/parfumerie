<?php
/**
 * Fonctions utilitaires globales
 * TATAVERNIS - Maison de Parfumerie Premium
 */

/**
 * Formater un prix
 */
function formatPrice($price, $showCurrency = true): string
{
    $formatted = number_format((float)$price, 0, ',', ' ');
    return $showCurrency ? $formatted . ' ' . CURRENCY : $formatted;
}

/**
 * Formater une date
 */
function formatDate($date, $format = 'd/m/Y'): string
{
    if (!$date) return '';
    $timestamp = is_string($date) ? strtotime($date) : $date;
    return date($format, $timestamp);
}

/**
 * Formater une date relative (il y a X minutes/heures/jours)
 */
function timeAgo($datetime): string
{
    $time = is_string($datetime) ? strtotime($datetime) : $datetime;
    $diff = time() - $time;

    if ($diff < 60) {
        return 'À l\'instant';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return "Il y a {$mins} minute" . ($mins > 1 ? 's' : '');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Il y a {$hours} heure" . ($hours > 1 ? 's' : '');
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return "Il y a {$days} jour" . ($days > 1 ? 's' : '');
    } else {
        return formatDate($datetime);
    }
}

/**
 * Tronquer un texte
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Obtenir l'URL d'une image uploadée
 */
function uploadUrl(string $path): string
{
    if (empty($path)) {
        return ASSETS_URL . '/images/placeholder.jpg';
    }
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    return UPLOADS_URL . '/' . $path;
}

/**
 * Obtenir l'URL d'un asset
 */
function asset(string $path): string
{
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Générer une URL de page
 */
function url(string $path = ''): string
{
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * Vérifier si on est sur la page courante
 */
function isCurrentPage(string $page): bool
{
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page;
}

/**
 * Ajouter une classe active si on est sur la page
 */
function activeClass(string $page, string $class = 'active'): string
{
    return isCurrentPage($page) ? $class : '';
}

/**
 * Afficher les étoiles de notation
 */
function ratingStars(float $rating, bool $showCount = false, int $count = 0): string
{
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    $html = '<div class="flex items-center gap-1">';
    
    // Étoiles pleines
    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '<svg class="w-4 h-4 text-gold fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
    }
    
    // Demi-étoile
    if ($halfStar) {
        $html .= '<svg class="w-4 h-4 text-gold" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half)" stroke="currentColor" stroke-width="1" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
    }
    
    // Étoiles vides
    for ($i = 0; $i < $emptyStars; $i++) {
        $html .= '<svg class="w-4 h-4 text-gray-600" viewBox="0 0 20 20"><path fill="none" stroke="currentColor" stroke-width="1" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
    }

    if ($showCount && $count > 0) {
        $html .= '<span class="text-sm text-gray-400 ml-1">(' . $count . ')</span>';
    }

    $html .= '</div>';

    return $html;
}

/**
 * Obtenir le badge de statut de commande
 */
function orderStatusBadge(string $status): string
{
    $colors = [
        'pending' => 'bg-yellow-500/20 text-yellow-400',
        'confirmed' => 'bg-blue-500/20 text-blue-400',
        'processing' => 'bg-purple-500/20 text-purple-400',
        'shipped' => 'bg-indigo-500/20 text-indigo-400',
        'delivered' => 'bg-green-500/20 text-green-400',
        'cancelled' => 'bg-red-500/20 text-red-400',
        'refunded' => 'bg-gray-500/20 text-gray-400'
    ];

    $label = ORDER_STATUS[$status] ?? $status;
    $color = $colors[$status] ?? 'bg-gray-500/20 text-gray-400';

    return '<span class="px-3 py-1 rounded-full text-xs font-medium ' . $color . '">' . $label . '</span>';
}

/**
 * Obtenir le badge de statut de paiement
 */
function paymentStatusBadge(string $status): string
{
    $colors = [
        'pending' => 'bg-yellow-500/20 text-yellow-400',
        'paid' => 'bg-green-500/20 text-green-400',
        'failed' => 'bg-red-500/20 text-red-400',
        'refunded' => 'bg-gray-500/20 text-gray-400'
    ];

    $label = PAYMENT_STATUS[$status] ?? $status;
    $color = $colors[$status] ?? 'bg-gray-500/20 text-gray-400';

    return '<span class="px-3 py-1 rounded-full text-xs font-medium ' . $color . '">' . $label . '</span>';
}

/**
 * Afficher un message flash
 */
function flash(string $key, string $message, string $type = 'success'): void
{
    $_SESSION['flash'][$key] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Récupérer et supprimer un message flash
 */
function getFlash(string $key): ?array
{
    if (isset($_SESSION['flash'][$key])) {
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
    return null;
}

/**
 * Afficher tous les messages flash
 */
function displayFlashes(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }

    $html = '';
    $icons = [
        'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        'error' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        'warning' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        'info' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    ];

    $colors = [
        'success' => 'bg-green-500/20 border-green-500/50 text-green-400',
        'error' => 'bg-red-500/20 border-red-500/50 text-red-400',
        'warning' => 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400',
        'info' => 'bg-blue-500/20 border-blue-500/50 text-blue-400'
    ];

    foreach ($_SESSION['flash'] as $key => $flash) {
        $type = $flash['type'];
        $icon = $icons[$type] ?? $icons['info'];
        $color = $colors[$type] ?? $colors['info'];

        $html .= '<div class="flash-message mb-4 p-4 rounded-lg border ' . $color . ' flex items-center gap-3" data-aos="fade-down">';
        $html .= $icon;
        $html .= '<span>' . Security::e($flash['message']) . '</span>';
        $html .= '<button type="button" class="ml-auto hover:opacity-70" onclick="this.parentElement.remove()">';
        $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        $html .= '</button>';
        $html .= '</div>';
    }

    $_SESSION['flash'] = [];
    return $html;
}

/**
 * Générer la pagination
 */
function pagination(int $currentPage, int $totalPages, string $baseUrl, array $params = []): string
{
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<nav class="flex items-center justify-center gap-2 mt-8">';

    // Bouton précédent
    if ($currentPage > 1) {
        $params['page'] = $currentPage - 1;
        $prevUrl = $baseUrl . '?' . http_build_query($params);
        $html .= '<a href="' . $prevUrl . '" class="w-10 h-10 flex items-center justify-center rounded-lg bg-muted hover:bg-gold hover:text-secondary transition-colors">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>';
        $html .= '</a>';
    }

    // Pages
    $range = 2;
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
            $params['page'] = $i;
            $pageUrl = $baseUrl . '?' . http_build_query($params);
            $activeClass = $i == $currentPage ? 'bg-gold text-secondary' : 'bg-muted hover:bg-gold/20';
            $html .= '<a href="' . $pageUrl . '" class="w-10 h-10 flex items-center justify-center rounded-lg ' . $activeClass . ' transition-colors">' . $i . '</a>';
        } elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1) {
            $html .= '<span class="px-2 text-gray-500">...</span>';
        }
    }

    // Bouton suivant
    if ($currentPage < $totalPages) {
        $params['page'] = $currentPage + 1;
        $nextUrl = $baseUrl . '?' . http_build_query($params);
        $html .= '<a href="' . $nextUrl . '" class="w-10 h-10 flex items-center justify-center rounded-lg bg-muted hover:bg-gold hover:text-secondary transition-colors">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
        $html .= '</a>';
    }

    $html .= '</nav>';

    return $html;
}

/**
 * Générer un numéro WhatsApp pour le chat
 */
function whatsappLink(string $message = ''): string
{
    $phone = preg_replace('/[^0-9]/', '', SITE_WHATSAPP);
    $url = 'https://wa.me/' . $phone;
    if ($message) {
        $url .= '?text=' . urlencode($message);
    }
    return $url;
}

/**
 * Charger un fichier d'autoload des classes
 */
spl_autoload_register(function ($class) {
    $file = CLASSES_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
