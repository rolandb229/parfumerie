<?php
/**
 * TATAVERNIS - Fichier de test pour vérification
 * À supprimer après vérification
 */

// Afficher les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test TATAVERNIS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #C8A96B; }
        .test-item { margin: 15px 0; padding: 10px; border-left: 3px solid #C8A96B; }
        .pass { color: green; }
        .fail { color: red; }
    </style>
</head>
<body>
    <h1>Vérification TATAVERNIS</h1>";

// Test 1: Configuration
echo "<div class='test-item'>";
echo "<strong>1. Configuration de base:</strong> ";
try {
    require_once 'config/config.php';
    echo "<span class='pass'>✓ OK</span>";
} catch (Exception $e) {
    echo "<span class='fail'>✗ ERREUR - " . $e->getMessage() . "</span>";
}
echo "</div>";

// Test 2: Base de données
echo "<div class='test-item'>";
echo "<strong>2. Connexion base de données:</strong> ";
try {
    require_once 'classes/Database.php';
    $db = Database::getInstance();
    $result = $db->fetch("SELECT 1 as test");
    if ($result) {
        echo "<span class='pass'>✓ OK</span>";
    } else {
        echo "<span class='fail'>✗ ERREUR - Base non accessible</span>";
    }
} catch (PDOException $e) {
    echo "<span class='fail'>✗ ERREUR - " . $e->getMessage() . "</span>";
}
echo "</div>";

// Test 3: Classes
echo "<div class='test-item'>";
echo "<strong>3. Classes PHP:</strong> ";
try {
    require_once 'classes/Security.php';
    require_once 'classes/Auth.php';
    require_once 'classes/Product.php';
    require_once 'classes/Cart.php';
    echo "<span class='pass'>✓ OK</span>";
} catch (Exception $e) {
    echo "<span class='fail'>✗ ERREUR - " . $e->getMessage() . "</span>";
}
echo "</div>";

// Test 4: Fonctions
echo "<div class='test-item'>";
echo "<strong>4. Fonctions utilitaires:</strong> ";
try {
    require_once 'includes/functions.php';
    $test = formatPrice(100000);
    if ($test) {
        echo "<span class='pass'>✓ OK</span>";
    } else {
        echo "<span class='fail'>✗ ERREUR - Fonction non trouvée</span>";
    }
} catch (Exception $e) {
    echo "<span class='fail'>✗ ERREUR - " . $e->getMessage() . "</span>";
}
echo "</div>";

// Test 5: Sessions
echo "<div class='test-item'>";
echo "<strong>5. Gestion des sessions:</strong> ";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<span class='pass'>✓ OK</span>";
} else {
    echo "<span class='fail'>✗ ERREUR - Sessions non actives</span>";
}
echo "</div>";

// Test 6: Constantes
echo "<div class='test-item'>";
echo "<strong>6. Constantes définies:</strong> ";
$constants = ['SITE_NAME', 'SITE_URL', 'CURRENCY', 'DEBUG_MODE'];
$missing = [];
foreach ($constants as $const) {
    if (!defined($const)) {
        $missing[] = $const;
    }
}
if (empty($missing)) {
    echo "<span class='pass'>✓ OK</span>";
} else {
    echo "<span class='fail'>✗ ERREUR - Constantes manquantes: " . implode(', ', $missing) . "</span>";
}
echo "</div>";

// Infos système
echo "<div class='test-item' style='background: #f0f0f0; margin-top: 30px;'>";
echo "<strong>Infos système:</strong><br>";
echo "PHP: " . phpversion() . "<br>";
echo "MySQL: " . (extension_loaded('pdo_mysql') ? "✓" : "✗") . "<br>";
echo "Serveur: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "</div>";

echo "</body></html>";
?>
