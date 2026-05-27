<?php
/**
 * Test de vérification des erreurs d'inclusion
 * Teste si toutes les classes se chargent correctement sans doublons
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Chargement des Classes</h2>";
echo "<pre>";

try {
    echo "[1/8] Chargement Database.php... ";
    require_once 'classes/Database.php';
    echo "✓ OK\n";

    echo "[2/8] Chargement Security.php... ";
    require_once 'classes/Security.php';
    echo "✓ OK\n";

    echo "[3/8] Chargement Product.php... ";
    require_once 'classes/Product.php';
    echo "✓ OK\n";

    echo "[4/8] Chargement Auth.php... ";
    require_once 'classes/Auth.php';
    echo "✓ OK\n";

    echo "[5/8] Chargement Cart.php... ";
    require_once 'classes/Cart.php';
    echo "✓ OK\n";

    echo "[6/8] Chargement Order.php... ";
    require_once 'classes/Order.php';
    echo "✓ OK\n";

    echo "[7/8] Chargement Category.php... ";
    require_once 'classes/Category.php';
    echo "✓ OK\n";

    echo "[8/8] Chargement Blog.php... ";
    require_once 'classes/Blog.php';
    echo "✓ OK\n";

    echo "\n=== TOUTES LES CLASSES CHARGÉES AVEC SUCCÈS ===\n\n";

    // Tester les méthodes clés
    echo "Vérification des méthodes critiques:\n";

    if (method_exists('Product', 'getBestSellers')) {
        echo "✓ Product::getBestSellers() existe\n";
    }

    if (method_exists('Product', 'getBestsellers')) {
        echo "✓ Product::getBestsellers() existe\n";
    }

    if (method_exists('Cart', 'getTotal')) {
        echo "✓ Cart::getTotal() existe\n";
    }

    if (method_exists('Cart', 'getTotals')) {
        echo "✓ Cart::getTotals() existe\n";
    }

    echo "\n=== TEST RÉUSSI ===\n";

} catch (Error $e) {
    echo "✗ ERREUR\n";
    echo "\nERREUR DÉTECTÉE:\n";
    echo $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}

echo "</pre>";
?>
