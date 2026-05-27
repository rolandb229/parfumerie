<?php
/**
 * TATAVERNIS - API Login
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Auth.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$remember = $input['remember'] ?? false;

if (empty($email) || empty($password)) {
    Security::jsonResponse(['success' => false, 'message' => 'Email et mot de passe requis']);
}

$auth = Auth::getInstance();
$result = $auth->login($email, $password, $remember);

if ($result['success']) {
    Security::jsonResponse([
        'success' => true,
        'message' => 'Connexion réussie',
        'user' => [
            'id' => $result['user']['id'],
            'first_name' => $result['user']['first_name'],
            'last_name' => $result['user']['last_name'],
            'email' => $result['user']['email']
        ]
    ]);
} else {
    Security::jsonResponse([
        'success' => false,
        'message' => implode(' ', $result['errors'])
    ]);
}
