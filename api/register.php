<?php
/**
 * TATAVERNIS - API Register
 */

header('Content-Type: application/json');

require_once '../config/config.php';
require_once '../classes/Auth.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

$data = [
    'first_name' => trim($input['first_name'] ?? ''),
    'last_name' => trim($input['last_name'] ?? ''),
    'email' => trim($input['email'] ?? ''),
    'phone' => trim($input['phone'] ?? ''),
    'password' => $input['password'] ?? '',
    'newsletter' => $input['newsletter'] ?? false
];

$auth = Auth::getInstance();
$result = $auth->register($data);

if ($result['success']) {
    Security::jsonResponse([
        'success' => true,
        'message' => 'Compte créé avec succès',
        'user_id' => $result['user_id']
    ]);
} else {
    Security::jsonResponse([
        'success' => false,
        'message' => implode(' ', $result['errors'])
    ]);
}
