<p align="center">
  <img src="public/images/logo.png" alt="Logo File d'Attente" width="180">
</p>

<h1 align="center">🎫 File d'Attente Virtuelle</h1>

<p align="center">
  <strong>Application de gestion de files d'attente - Projet ESPRIT</strong><br>
  <em>Module : Programmation Web 2 | Année : 2025/2026</em>
</p>

<p align="center">
  <a href="#-fonctionnalités">Fonctionnalités</a> •
  <a href="#-technologies">Technologies</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-structure-du-projet">Structure</a> •
  <a href="#-répartition-tp">Répartition TP</a> •
  <a href="#-équipe">Équipe</a>
</p>

---

## 📋 Description du Projet

**File d'Attente Virtuelle** est une application web développée avec **Laravel 11** permettant aux usagers de :
- 🎫 Prendre un ticket en ligne pour un service spécifique
- 📊 Suivre l'état de leur ticket en temps réel
- 🔍 Rechercher et filtrer les tickets par nom, numéro ou statut
- ✏️ Modifier le statut d'un ticket (pour les agents)
- 🗑️ Supprimer un ticket si nécessaire

L'application respecte une architecture **MVC** conforme aux bonnes pratiques Laravel et aux exigences des TP2, TP3, TP4 et TP5.

---

## ✨ Fonctionnalités

### 👤 Côté Client
| Fonctionnalité | Description | Statut |
|---------------|-------------|--------|
| 🏠 Page d'accueil | Présentation du projet avec accès rapide aux fonctionnalités | ✅ |
| 🎫 Prendre un ticket | Formulaire sécurisé avec validation serveur | ✅ |
| 📋 Liste des tickets | Affichage paginé avec recherche et filtres | ✅ |
| 👁️ Voir un ticket | Page de détails avec informations complètes | ✅ |

### 👨‍💼 Côté Administration
| Fonctionnalité | Description | Statut |
|---------------|-------------|--------|
| ✏️ Modifier un ticket | Changement de statut avec timestamps automatiques | ✅ |
| 🗑️ Supprimer un ticket | Suppression avec confirmation et soft delete | ✅ |
| 🔍 Recherche avancée | Filtrage par texte et par statut | ✅ |
| 📄 Pagination | Navigation fluide avec `withQueryString()` | ✅ |

### 🔐 Sécurité & Qualité
- ✅ Validation des formulaires avec `$request->validate()`
- ✅ Protection CSRF avec `@csrf`
- ✅ Model Binding pour les routes CRUD
- ✅ Relations Eloquent optimisées (`with()`, `load()`)
- ✅ Messages flash pour les feedbacks utilisateur

---

## 🛠️ Technologies Utilisées

| Catégorie | Technologies |
|-----------|-------------|
| **Backend** | Laravel 11, PHP 8.2+, Eloquent ORM |
| **Frontend** | Blade Templates, Bootstrap 5.3, Font Awesome 6 |
| **Base de données** | MySQL 8.0, Migrations Laravel |
| **Serveur** | XAMPP (Apache + MySQL), `php artisan serve` |
| **Outils** | Composer, Git, VS Code, phpMyAdmin |
| **Design** | CSS personnalisé (variables, animations, responsive) |

---

## 🚀 Installation & Configuration

### Prérequis
- PHP ≥ 8.2
- Composer ≥ 2.0
- MySQL ≥ 8.0
- XAMPP ou équivalent

### Étapes d'installation

```bash
# 1. Cloner le projet (ou copier les fichiers)
cd C:\xampp\htdocs
# Copier le dossier du projet ici

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env.example .env
# Éditer .env avec tes paramètres :
# DB_DATABASE=file_attente_db
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Générer la clé d'application
php artisan key:generate

# 5. Lancer les migrations
php artisan migrate

# 6. (Optionnel) Ajouter des services de test
php artisan tinker
>>> App\Models\Service::create(['name' => 'Inscription', 'description' => 'Service d\'inscription']);
>>> exit

# 7. Lancer le serveur de développement
php artisan serve