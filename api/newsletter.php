<?php
/**
 * TATAVERNIS - API Newsletter
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (!Security::validateEmail($email)) {
    Security::jsonResponse(['success' => false, 'message' => 'Adresse email invalide']);
}

$db = Database::getInstance();

// Verifier si deja inscrit
$existing = $db->fetch(
    "SELECT id, is_active FROM newsletter_subscribers WHERE email = ?",
    [$email]
);

if ($existing) {
    if ($existing['is_active']) {
        Security::jsonResponse(['success' => false, 'message' => 'Vous êtes déjà inscrit à notre newsletter']);
    } else {
        // Reactiver
        $db->update('newsletter_subscribers', ['is_active' => 1, 'unsubscribed_at' => null], 'id = ?', [$existing['id']]);
        Security::jsonResponse(['success' => true, 'message' => 'Votre inscription a été réactivée']);
    }
} else {
    // Nouvelle inscription
    $db->insert('newsletter_subscribers', [
        'email' => $email,
        'is_active' => 1
    ]);
    Security::jsonResponse(['success' => true, 'message' => 'Inscription réussie ! Merci de votre confiance.']);
}
