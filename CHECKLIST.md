# CHECKLIST DE VÉRIFICATION - TATAVERNIS

## Avant de lancer le site sur XAMPP

Cochez chaque élément pour vérifier que tout est prêt.

---

## Installation XAMPP

- [ ] XAMPP installé et fonctionnel
- [ ] Apache activé dans XAMPP
- [ ] MySQL activé dans XAMPP
- [ ] PHP 7.4+ disponible (`php -v`)
- [ ] Dossier `htdocs/parfumerie` créé et copié

---

## Configuration Base de Données

- [ ] Base de données `tatavernis` créée
- [ ] Identifiants MySQL corrects (root, sans mot de passe)
- [ ] Fichier config/database.php vérifiés:
  ```php
  DB_HOST = 'localhost'
  DB_NAME = 'tatavernis'
  DB_USER = 'root'
  DB_PASS = ''
  ```

---

## Fichiers Critiques Présents

### Config
- [ ] config/config.php (86 lignes)
- [ ] config/database.php (23 lignes)
- [ ] config/constants.php (128 lignes)

### Classes
- [ ] classes/Auth.php
- [ ] classes/Database.php
- [ ] classes/Product.php
- [ ] classes/Category.php
- [ ] classes/Cart.php
- [ ] classes/Order.php
- [ ] classes/Security.php
- [ ] classes/Blog.php
- [ ] classes/Upload.php

### Pages Frontend
- [ ] index.php (accueil)
- [ ] boutique.php (catalogue)
- [ ] produit.php (fiche produit)
- [ ] panier.php (panier)
- [ ] checkout.php (paiement)
- [ ] connexion.php (login)
- [ ] inscription.php (register)
- [ ] compte.php (profil)
- [ ] deconnexion.php (logout)

### API Endpoints
- [ ] api/login.php
- [ ] api/register.php
- [ ] api/add-to-cart.php
- [ ] api/remove-cart.php
- [ ] api/update-cart.php
- [ ] api/cart.php
- [ ] api/search.php
- [ ] api/wishlist.php
- [ ] api/newsletter.php

### Templates
- [ ] includes/header.php
- [ ] includes/footer.php
- [ ] includes/navbar.php
- [ ] includes/functions.php

### Autres
- [ ] .htaccess présent et correct
- [ ] test.php présent
- [ ] assets/ dossier présent

---

## Fichiers Supprimés (Nettoyage Next.js)

- [ ] components.json SUPPRIMÉ ✓
- [ ] Dépendances Next.js SUPPRIMÉES ✓

---

## Vérifications de Configuration

### URLs
- [ ] SITE_URL = 'http://localhost/parfumerie'
- [ ] DEBUG_MODE = true (pour développement)
- [ ] .htaccess RewriteBase = '/parfumerie/'

### Constantes
- [ ] CURRENCY = 'FCFA'
- [ ] FREE_SHIPPING_THRESHOLD = 50000
- [ ] SHIPPING_COST = 3000

### Sécurité
- [ ] CSRF tokens activés
- [ ] Hachage passwords (bcrypt)
- [ ] Échappement XSS (htmlspecialchars)
- [ ] Requêtes paramétrées (PDO)

---

## Permissions Fichiers (Linux/Mac)

```bash
chmod 755 /path/to/xampp/htdocs/parfumerie
chmod 755 /path/to/xampp/htdocs/parfumerie/uploads
chmod 644 /path/to/xampp/htdocs/parfumerie/*.php
```

- [ ] Dossier projet: drwxr-xr-x (755)
- [ ] Fichiers PHP: -rw-r--r-- (644)
- [ ] Dossier uploads: drwxr-xr-x (755) + writable

---

## Test de Connexion

1. [ ] Ouvrir navigateur: `http://localhost/parfumerie/test.php`

2. [ ] Vérifier les tests (tous doivent être verts):
   - [ ] 1. Configuration de base: ✓ OK
   - [ ] 2. Connexion base de données: ✓ OK
   - [ ] 3. Classes PHP: ✓ OK
   - [ ] 4. Fonctions utilitaires: ✓ OK
   - [ ] 5. Gestion des sessions: ✓ OK
   - [ ] 6. Constantes définies: ✓ OK

---

## Fonctionnement du Site

### Pages accessibles sans erreur

- [ ] http://localhost/parfumerie/ (accueil)
- [ ] http://localhost/parfumerie/boutique.php (catalogue)
- [ ] http://localhost/parfumerie/produit.php?slug=oud-royal (produit)
- [ ] http://localhost/parfumerie/panier.php (panier)
- [ ] http://localhost/parfumerie/connexion.php (connexion)
- [ ] http://localhost/parfumerie/inscription.php (inscription)

### Pas d'erreurs PHP

- [ ] Console PHP XAMPP vide
- [ ] Navigateur F12 → Console sans erreurs
- [ ] Pas de fichier error_log

### Fonctionnalités basiques

- [ ] Slider accueil marche
- [ ] Produits se chargent
- [ ] Panier de démo visible
- [ ] Formulaires visibles
- [ ] Images s'affichent (ou icônes de démo)
- [ ] Styles CSS appliqués

---

## Avant Production

- [ ] [ ] DEBUG_MODE = false dans config/config.php
- [ ] [ ] SITE_URL = 'https://tatavernis.ci'
- [ ] [ ] Ajouter mot de passe MySQL
- [ ] [ ] Configurer HTTPS dans .htaccess
- [ ] [ ] Sauvegarder base de données
- [ ] [ ] Supprimer test.php
- [ ] [ ] Modifier .env pour production

---

## Documentation

- [ ] README.md lu et compris
- [ ] INSTALLATION_XAMPP.md consulté si besoin
- [ ] CORRECTIONS.md pour détails des corrections
- [ ] SUMMARY.md pour vue d'ensemble

---

## Résolution des problèmes

Si une case n'est pas cochée:

1. [ ] Lancer test.php pour diagnostiquer
2. [ ] Vérifier logs PHP dans XAMPP
3. [ ] Consulter INSTALLATION_XAMPP.md
4. [ ] Vérifier console navigateur (F12)
5. [ ] Vérifier phpMyAdmin pour la BD

---

## Prêt pour démarrer

- [ ] Toutes les cases cochées
- [ ] test.php affiche 6 tests réussis
- [ ] Site accessible sur http://localhost/parfumerie/
- [ ] Aucune erreur PHP ou JavaScript

**Date**: ___/___/_____  
**Signé**: _____________________

---

**État**: À vérifier avant de commencer  
**Important**: Ne pas oublier test.php après les changements
