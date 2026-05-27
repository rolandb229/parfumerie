<?php
/**
 * DEBUG - Vérifier la configuration
 */

require_once 'config/config.php';

echo "<h1>Debug TATAVERNIS</h1>";
echo "<pre>";

echo "[v0] SITE_URL: " . SITE_URL . "\n";
echo "[v0] ROOT_PATH: " . ROOT_PATH . "\n";
echo "[v0] CLASSES_PATH: " . CLASSES_PATH . "\n";
echo "[v0] INCLUDES_PATH: " . INCLUDES_PATH . "\n";

echo "\n=== Vérification des fichiers ===" . "\n";
$files = [
    'config/database.php',
    'config/constants.php',
    'classes/Security.php',
    'classes/Product.php',
    'classes/Auth.php',
    'includes/functions.php',
    'includes/header.php',
    'includes/navbar.php',
    'includes/footer.php'
];

foreach ($files as $file) {
    $path = ROOT_PATH . '/' . $file;
    $exists = file_exists($path) ? "✓ OK" : "✗ MANQUANT";
    echo "[v0] $file: $exists\n";
}

echo "\n=== Vérification des classes ===" . "\n";
echo "[v0] Security class exists: " . (class_exists('Security') ? "✓ YES" : "✗ NO") . "\n";
echo "[v0] Database class exists: " . (class_exists('Database') ? "✓ YES" : "✗ NO") . "\n";
echo "[v0] Product class exists: " . (class_exists('Product') ? "✓ YES" : "✗ NO") . "\n";
echo "[v0] Auth class exists: " . (class_exists('Auth') ? "✓ YES" : "✗ NO") . "\n";

echo "\n=== Vérification des fonctions ===" . "\n";
echo "[v0] formatPrice exists: " . (function_exists('formatPrice') ? "✓ YES" : "✗ NO") . "\n";
echo "[v0] asset exists: " . (function_exists('asset') ? "✓ YES" : "✗ NO") . "\n";
echo "[v0] url exists: " . (function_exists('url') ? "✓ YES" : "✗ NO") . "\n";

echo "\n=== Test de base de données ===" . "\n";
try {
    $db = Database::getInstance();
    echo "[v0] Database connection: ✓ OK\n";
} catch (Exception $e) {
    echo "[v0] Database connection: ✗ ERREUR - " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<hr>";
echo "<h2>Accéder au site</h2>";
echo "<p><a href='" . SITE_URL . "'>Aller à l'accueil</a></p>";
