<?php
/**
 * TATAVERNIS - Déconnexion
 */

require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Security.php';

$auth = Auth::getInstance();
$auth->logout();

Security::redirect('/connexion.php');
