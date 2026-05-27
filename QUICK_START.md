# 🚀 DÉMARRAGE RAPIDE - TATAVERNIS

## 3 étapes pour lancer le site

### 1️⃣ Installer XAMPP

```bash
# Télécharger et installer XAMPP depuis: https://www.apachefriends.org
# Ou sur Mac: brew install xampp
```

### 2️⃣ Copier le projet

```bash
# Copier le dossier parfumerie dans htdocs
cp -r parfumerie /path/to/xampp/htdocs/

# Ou sur XAMPP standard:
# Windows: C:\xampp\htdocs\parfumerie
# Mac: /Applications/XAMPP/xamppfiles/htdocs/parfumerie
# Linux: /opt/lampp/htdocs/parfumerie
```

### 3️⃣ Lancer et vérifier

```bash
# 1. Démarrer XAMPP Control Panel
# 2. Cliquer "Start" pour Apache
# 3. Cliquer "Start" pour MySQL
# 4. Ouvrir dans le navigateur:
# http://localhost/parfumerie/
```

---

## ✅ Vérifier l'installation

```
http://localhost/parfumerie/test.php
```

Vous devez voir **6 tests réussis** ✓

---

## 📂 Créer la base de données

```
1. Ouvrir phpMyAdmin: http://localhost/phpmyadmin
2. Cliquer "Nouvelle base de données"
3. Nom: tatavernis
4. Cliquer "Créer"
```

C'est tout! La base est prête.

---

## 🎯 Accéder au site

```
Frontend:  http://localhost/parfumerie/
Admin:     http://localhost/parfumerie/admin/
Test:      http://localhost/parfumerie/test.php
```

---

## 📖 Pour plus d'infos

| Fichier | Contenu |
|---------|---------|
| **README.md** | Guide complet |
| **INSTALLATION_XAMPP.md** | Détails installation |
| **CHECKLIST.md** | Vérifications |
| **test.php** | Diagnostic auto |

---

## ⚡ Données de démo

Le site inclut des données de démo:
- Produits Bestsellers et Nouveautés
- Panier avec articles d'exemple
- Fiches produits complètes

Idéal pour tester sans base de données!

---

## 🔧 Configuration (optionnel)

Modifier dans `config/config.php`:

```php
// Changer l'URL du site
define('SITE_URL', 'http://localhost/parfumerie');

// Activer/désactiver le mode debug
define('DEBUG_MODE', true);  // false en production
```

---

## ❌ Problèmes courants

| Problème | Solution |
|----------|----------|
| "Cannot connect to database" | Vérifier que MySQL est lancé |
| Erreur 404 | Vérifier que .htaccess existe |
| Images ne s'affichent pas | Vérifier dossier assets/ |
| Pages blanches | Vérifier error_log XAMPP |

---

## 💡 Besoin d'aide?

1. Ouvrir `http://localhost/parfumerie/test.php`
2. Lire le rapport de diagnostic
3. Consulter `INSTALLATION_XAMPP.md`
4. Vérifier les logs PHP dans XAMPP

---

## ✨ C'est prêt!

Vous pouvez maintenant:
- Parcourir le catalogue
- Ajouter des produits au panier
- Vous inscrire/vous connecter
- Tester les fonctionnalités

**Bon shopping! 🛍️**

---

**État**: PRÊT POUR XAMPP LOCAL  
**Version**: 1.0.0
