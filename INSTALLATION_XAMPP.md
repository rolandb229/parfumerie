# TATAVERNIS - Guide d'Installation sur XAMPP

## Prérequis
- XAMPP installé (avec PHP 7.4+, MySQL)
- Apache activé dans XAMPP
- MySQL activé dans XAMPP

## Étapes d'installation

### 1. Placer les fichiers du projet
```bash
# Copier le dossier du projet dans htdocs
cp -r parfumerie /path/to/xampp/htdocs/
```

### 2. Créer la base de données

#### Option A: Via phpMyAdmin
1. Ouvrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Créer une nouvelle base de données nommée `tatavernis`
3. Importer le fichier `database/schema.sql` (si disponible)

#### Option B: Via la ligne de commande
```bash
mysql -u root -p < database/schema.sql
```

### 3. Configuration de la base de données
Le fichier `config/database.php` est déjà configuré pour XAMPP:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tatavernis');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vide par défaut dans XAMPP
```

Si vous avez un mot de passe MySQL, modifiez `DB_PASS`.

### 4. Démarrer XAMPP
1. Ouvrir le Panneau de Contrôle XAMPP
2. Cliquer sur "Start" pour Apache
3. Cliquer sur "Start" pour MySQL

### 5. Accéder au site
- **URL locale**: `http://localhost/parfumerie/`
- **phpMyAdmin**: `http://localhost/phpmyadmin`

## Vérification de l'installation

Après l'installation, vérifier que:
- ✓ La page d'accueil s'affiche sans erreur
- ✓ Les produits se chargent correctement
- ✓ La base de données est accessible
- ✓ Les sessions utilisateur fonctionnent

## Troubleshooting

### Erreur: "Cannot connect to database"
- Vérifier que MySQL est activé dans XAMPP
- Vérifier les identifiants dans `config/database.php`
- Vérifier que la base `tatavernis` existe

### Erreur: "Class not found"
- Vérifier que tous les fichiers PHP sont présents
- Vérifier que `config/config.php` charge bien les chemins

### Images ne s'affichent pas
- Vérifier que le dossier `assets/` existe
- Vérifier les permissions de lecture sur le dossier

### Sessions ne fonctionnent pas
- Vérifier que `session_start()` est appelé dans `config/config.php`
- Vérifier que le dossier `uploads/` est writable

## Structure des dossiers

```
parfumerie/
├── config/              # Configuration
│   ├── config.php      # Configuration principale
│   ├── database.php    # Configuration BD
│   └── constants.php   # Constantes
├── classes/            # Classes PHP
├── includes/           # Includes HTML
├── api/               # API endpoints
├── admin/             # Panneau admin
├── assets/            # CSS, JS, images
├── uploads/           # Uploads utilisateur
├── index.php          # Page d'accueil
├── boutique.php       # Catalogue produits
├── produit.php        # Fiche produit
├── panier.php         # Panier
├── checkout.php       # Paiement
└── connexion.php      # Authentification
```

## Configuration Apache (si besoin)

Si les URL propres ne fonctionnent pas, vérifier que:
1. Le module `mod_rewrite` est activé
2. Le fichier `.htaccess` est présent et correct
3. L'option `AllowOverride All` est activée pour le VirtualHost

## Production

**IMPORTANT**: Avant de passer en production:
1. Modifier `DEBUG_MODE` à `false` dans `config/config.php`
2. Utiliser HTTPS (modifier les redirects dans `.htaccess`)
3. Ajouter un mot de passe MySQL
4. Modifier les configurations de sécurité
5. Sauvegarder régulièrement la base de données

## Support

Pour toute question ou problème, vérifier:
- Le fichier `DIAGNOSTIC.md` pour les problèmes connus
- Les logs d'erreur PHP dans XAMPP
- La console du navigateur (F12) pour les erreurs JavaScript
