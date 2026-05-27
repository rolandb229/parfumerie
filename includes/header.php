<?php
/**
 * Header HTML - En-tête de toutes les pages
 * TATAVERNIS - Maison de Parfumerie Premium
 */

// Variables par défaut
$pageTitle = $pageTitle ?? SITE_NAME;
$pageDescription = $pageDescription ?? SITE_DESCRIPTION;
$pageImage = $pageImage ?? ASSETS_URL . '/images/og-image.jpg';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= Security::e($pageTitle) ?> | <?= SITE_NAME ?></title>
    <meta name="description" content="<?= Security::e($pageDescription) ?>">
    <meta name="keywords" content="parfum, parfumerie, luxe, fragrance, TATAVERNIS, Côte d'Ivoire, Abidjan, parfum homme, parfum femme">
    <meta name="author" content="<?= SITE_NAME ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= Security::e(SITE_URL . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:title" content="<?= Security::e($pageTitle) ?>">
    <meta property="og:description" content="<?= Security::e($pageDescription) ?>">
    <meta property="og:image" content="<?= Security::e($pageImage) ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= Security::e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= Security::e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= Security::e($pageImage) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= asset('images/apple-touch-icon.png') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN pour le développement) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0B0B0B',
                        'secondary': '#0B0B0B',
                        'gold': '#C8A96B',
                        'gold-light': '#D4B87A',
                        'gold-dark': '#A8894B',
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
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?= Security::generateCsrfToken() ?>">
</head>
<body class="bg-primary text-cream antialiased <?= Security::e($bodyClass) ?>">
    
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[100] bg-primary flex items-center justify-center transition-opacity duration-500">
        <div class="text-center">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/nouveau_logo_tata_vernis_copie%5B1%5D-MYSK5d7Sa5b4cQPhYsEszEKpB1EC51.png" alt="<?= SITE_NAME ?>" class="h-16 mb-4 animate-pulse">
            <div class="w-48 h-1 bg-muted rounded-full overflow-hidden">
                <div class="h-full bg-gold animate-[loading_1.5s_ease-in-out_infinite]"></div>
            </div>
        </div>
    </div>

    <!-- Skip to content (Accessibility) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-gold text-primary px-4 py-2 rounded-lg z-50">
        Aller au contenu principal
    </a>

    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- Main Content -->
    <main id="main-content">
