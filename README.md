# 🚨 BNGRC - Système de Gestion des Sinistres

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.0%2B-blue.svg)](https://www.php.net/)
[![Flight PHP](https://img.shields.io/badge/Framework-Flight%20PHP-green.svg)](https://flightphp.com/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![MySQL](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://www.mysql.com/)

Système de gestion des sinistres et catastrophes naturelles pour le **Bureau National de Gestion des Risques et des Catastrophes (BNGRC)** de Madagascar.

## 📋 Table des Matières

- [Aperçu](#-aperçu)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du Projet](#-structure-du-projet)
- [Base de Données](#-base-de-données)
- [API Routes](#-api-routes)
- [Contribuer](#-contribuer)
- [Licence](#-licence)

## 🌟 Aperçu

Cette application web permet de gérer efficacement les sinistres et catastrophes naturelles à Madagascar. Elle offre une interface moderne pour :

- Enregistrer et suivre les sinistres par ville et région
- Gérer les besoins des populations sinistrées
- Visualiser les statistiques en temps réel
- Coordonner les interventions d'urgence

### Captures d'écran

```
┌─────────────────────────────────────────┐
│  📊 Dashboard - Vue d'ensemble          │
│  • Statistiques des sinistres          │
│  • Besoins par catégorie               │
│  • Top régions affectées               │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  📝 Gestion des Sinistres               │
│  • Liste avec filtres                  │
│  • Ajout/Modification/Suppression      │
│  • Vue détaillée + besoins             │
└─────────────────────────────────────────┘
```

## ✨ Fonctionnalités

### ✅ Implémentées

- **Dashboard interactif**
  - 📈 Statistiques en temps réel (sinistres, villes, régions, besoins)
  - 📊 Visualisation des besoins par catégorie
  - 🗺️ Top 5 des régions les plus affectées
  - 📋 Liste des sinistres récents

- **Gestion des Sinistres**
  - ➕ Création de nouveaux sinistres
  - 👁️ Visualisation détaillée
  - ✏️ Modification des informations
  - 🗑️ Suppression
  - 📅 Suivi avec date et description

- **Gestion des Besoins**
  - 📦 Association des besoins aux sinistres
  - 🏷️ Catégorisation (nourriture, eau, médicaments, etc.)
  - 📊 Quantification des ressources nécessaires

- **Interface Moderne**
  - 🎨 Design Flexy Bootstrap responsive
  - 🌙 Thème bleu navy/noir professionnel
  - 📱 Compatible mobile et tablette
  - ⚡ Assets optimisés (70% de réduction)

### 🔄 En Développement

- [ ] Module de gestion des dons
- [ ] Système de notifications
- [ ] Export de rapports (PDF, Excel)
- [ ] Authentification et rôles utilisateurs
- [ ] Historique des modifications

## 🛠️ Technologies

### Backend
- **[Flight PHP](https://flightphp.com/)** v3.9+ - Micro-framework PHP léger
- **PHP** 7.4+ / 8.0+ - Langage serveur
- **MySQL** 5.7+ / 8.0+ - Base de données relationnelle
- **PDO** - Couche d'abstraction de base de données

### Frontend
- **[Flexy Bootstrap Template](https://www.wrappixel.com/templates/flexy/)** - Template d'administration moderne
- **Bootstrap 5** - Framework CSS
- **Tabler Icons** - Bibliothèque d'icônes
- **jQuery** - Manipulation DOM
- **Simplebar.js** - Scrollbar personnalisée

### Outils de Développement
- **Composer** - Gestionnaire de dépendances PHP
- **Tracy** - Debugger et logger
- **Git** - Contrôle de version

## 📦 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 7.4 avec extensions :
  - `ext-json`
  - `ext-pdo`
  - `ext-pdo_mysql`
- **MySQL** >= 5.7 ou **MariaDB** >= 10.2
- **Composer** >= 2.0
- **Git** (optionnel, pour le développement)
- **Serveur Web** : Apache ou Nginx (ou PHP built-in server pour développement)

## 🚀 Installation

### 1. Cloner le Repository

```bash
git clone https://github.com/Toavina-Andria/BNGRC.git
cd BNGRC
```

### 2. Installer les Dépendances

```bash
composer install
```

### 3. Créer la Base de Données

```bash
# Connexion à MySQL
mysql -u root -p

# Créer la base de données
CREATE DATABASE bngrc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Importer le schéma
mysql -u root -p bngrc < script-database/2026-2-16_14:41-script.sql

# Exécuter la migration (ajout de champs)
mysql -u root -p bngrc < script-database/2026-2-16_migration-add-fields.sql

# Créer la vue sinistre_details
mysql -u root -p bngrc < script-database/2026-2-16_18:06-view.sql

# (Optionnel) Insérer des données de test
mysql -u root -p bngrc < script-database/2026-2-16_test-data.sql
```

### 4. Configuration

Copiez le fichier de configuration exemple et modifiez-le :

```bash
cp app/config/config_sample.php app/config/config.php
```

Éditez `app/config/config.php` avec vos paramètres :

```php
<?php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'bngrc',
        'user' => 'votre_utilisateur',
        'password' => 'votre_mot_de_passe'
    ]
];
```

### 5. Créer les Dossiers Nécessaires

```bash
mkdir -p app/cache app/log
chmod 755 app/cache app/log
```

### 6. Lancer le Serveur

#### Option 1 : Serveur PHP intégré (développement)

```bash
composer start
# ou
php -S localhost:8000 -t public
```

#### Option 2 : Apache

Configurez votre VirtualHost pointant vers le dossier `public/`

```apache
<VirtualHost *:80>
    ServerName bngrc.local
    DocumentRoot "/chemin/vers/BNGRC/public"
    
    <Directory "/chemin/vers/BNGRC/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Option 3 : Nginx

```nginx
server {
    listen 80;
    server_name bngrc.local;
    root /chemin/vers/BNGRC/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 7. Accéder à l'Application

Ouvrez votre navigateur et accédez à :
- Serveur PHP intégré : `http://localhost:8000`
- VirtualHost : `http://bngrc.local`

## ⚙️ Configuration

### Variables d'Environnement

Le fichier `app/config/config.php` supporte les configurations suivantes :

```php
return [
    'database' => [
        'host' => 'localhost',        // Hôte MySQL
        'dbname' => 'bngrc',          // Nom de la base
        'user' => 'root',             // Utilisateur
        'password' => 'password'      // Mot de passe
    ],
    'app' => [
        'debug' => true,              // Mode debug (Tracy)
        'timezone' => 'Indian/Antananarivo'
    ]
];
```

### Mode Debug

En développement, Tracy Debugger est activé automatiquement. Les logs sont stockés dans `app/log/`.

**⚠️ En production** : Désactivez le mode debug dans `app/config/services.php` :

```php
Debugger::$productionMode = true;
```

## 📖 Utilisation

### Créer un Sinistre

1. Accédez au Dashboard
2. Cliquez sur **"+ Nouveau Sinistre"**
3. Remplissez le formulaire :
   - Sélectionnez la ville affectée
   - Indiquez le nombre de sinistres
   - Ajoutez la date et une description
4. Cliquez sur **"Enregistrer"**

### Ajouter un Besoin

1. Depuis le Dashboard ou la liste des sinistres
2. Cliquez sur **"+ Ajouter un besoin"**
3. Remplissez le formulaire :
   - Sélectionnez le sinistre concerné
   - Choisissez la catégorie de besoin
   - Indiquez la quantité nécessaire
   - Ajoutez une description
4. Cliquez sur **"Enregistrer"**

### Consulter les Statistiques

Le Dashboard affiche automatiquement :
- Nombre total de sinistres
- Villes et régions affectées
- Total des besoins recensés
- Répartition par catégorie
- Top 5 des régions

## 📂 Structure du Projet

```
BNGRC/
├── app/
│   ├── cache/                 # Cache de l'application
│   ├── commands/              # Commandes CLI Runway
│   ├── config/                # Fichiers de configuration
│   │   ├── bootstrap.php      # Bootstrap de l'application
│   │   ├── routes.php         # Définition des routes
│   │   └── services.php       # Container de services
│   ├── controllers/           # Controllers MVC
│   │   ├── DashboardController.php
│   │   ├── SinistreController.php
│   │   ├── InsertionController.php
│   │   └── BesoinController.php
│   ├── log/                   # Logs Tracy
│   ├── middlewares/           # Middlewares HTTP
│   ├── models/                # Modèles de données
│   │   ├── Sinistre.php
│   │   ├── Ville.php
│   │   ├── Region.php
│   │   ├── CategorieBesoin.php
│   │   └── SinistreBesoin.php
│   ├── services/              # Services métier
│   │   └── SinistreService.php
│   ├── utils/                 # Utilitaires
│   └── views/                 # Vues PHP
│       ├── dashboard/
│       │   ├── dashboard.php
│       │   └── partial/
│       └── sinistre/
│           ├── liste.php
│           ├── form.php
│           ├── view.php
│           ├── edit.php
│           └── besoin_form.php
├── public/                    # Point d'entrée web
│   ├── index.php              # Front controller
│   └── assets/                # Assets statiques
│       ├── css/
│       ├── js/
│       └── libs/
├── script-database/           # Scripts SQL
│   ├── 2026-2-16_14:41-script.sql         # Schéma initial
│   ├── 2026-2-16_migration-add-fields.sql # Migration
│   ├── 2026-2-16_18:06-view.sql           # Vue sinistre_details
│   └── 2026-2-16_test-data.sql            # Données de test
├── vendor/                    # Dépendances Composer
├── composer.json              # Configuration Composer
├── IMPLEMENTATION.md          # Documentation technique
├── SUMMARY.md                 # Résumé des implémentations
├── todo.md                    # Liste des tâches
└── README.md                  # Ce fichier
```

## 🗄️ Base de Données

### Schéma

```sql
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│  bn_region  │       │  bn_ville   │       │ bn_sinistre │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │◄──┐   │ id (PK)     │◄──┐   │ id (PK)     │
│ nom         │   └───│ id_region   │   └───│ id_ville    │
└─────────────┘       │ nom         │       │ nb_sinistres│
                      │ population  │       │ date_sinistre│
                      └─────────────┘       │ description │
                                            └──────┬──────┘
                                                   │
                      ┌──────────────────┐    ┌───▼─────────────────┐
                      │ bn_categorie_    │    │ bn_sinistre_besoin  │
                      │     besoin       │    ├─────────────────────┤
                      ├──────────────────┤    │ id (PK)             │
                      │ id (PK)          │◄───│ id_sinistre (FK)    │
                      │ nom              │    │ id_categorie (FK)   │
                      └──────────────────┘    │ description         │
                                              │ quantite            │
                                              └─────────────────────┘
```

### Vue `sinistre_details`

Vue SQL combinant toutes les informations pour optimiser les requêtes :

```sql
SELECT
    s.id AS sinistre_id,
    s.nombre_sinistres,
    s.date_sinistre,
    s.description AS sinistre_description,
    v.nom AS ville_nom,
    v.population AS ville_population,
    r.nom AS region_nom,
    cb.nom AS categorie_nom,
    sb.description AS besoin_description,
    sb.quantite AS besoin_quantite
FROM bn_sinistre s
JOIN bn_ville v ON s.id_ville = v.id
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
LEFT JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id;
```

## 🛣️ API Routes

### Routes Publiques

| Méthode | Route | Controller | Action |
|---------|-------|------------|--------|
| GET | `/` | DashboardController | Affichage du tableau de bord |
| GET | `/sinistres/liste` | SinistreController | Liste des sinistres |
| GET | `/sinistres/view/{id}` | SinistreController | Détails d'un sinistre |
| GET | `/sinistres/insert` | InsertionController | Formulaire d'ajout |
| POST | `/sinistres/insert` | InsertionController | Traitement ajout |
| GET | `/sinistres/edit/{id}` | SinistreController | Formulaire de modification |
| POST | `/sinistres/update/{id}` | SinistreController | Traitement modification |
| GET | `/sinistres/delete/{id}` | SinistreController | Suppression |
| GET | `/sinistres/besoins/insert` | InsertionController | Formulaire besoin |
| POST | `/sinistres/besoins/insert` | InsertionController | Traitement besoin |
| GET | `/besoins/liste` | BesoinController | Liste des besoins |

## 🤝 Contribuer

Les contributions sont les bienvenues ! Voici comment procéder :

### 1. Fork et Clone

```bash
# Fork sur GitHub puis :
git clone https://github.com/VOTRE_USERNAME/BNGRC.git
cd BNGRC
git remote add upstream https://github.com/Toavina-Andria/BNGRC.git
```

### 2. Créer une Branche

```bash
git checkout -b feature/ma-nouvelle-fonctionnalite
# ou
git checkout -b fix/correction-bug
```

### 3. Coder et Commiter

```bash
git add .
git commit -m "feat: ajout de la fonctionnalité X"
```

**Convention de commits** :
- `feat:` - Nouvelle fonctionnalité
- `fix:` - Correction de bug
- `docs:` - Documentation
- `style:` - Formatage, style
- `refactor:` - Refactoring de code
- `test:` - Ajout de tests
- `chore:` - Maintenance

### 4. Push et Pull Request

```bash
git push origin feature/ma-nouvelle-fonctionnalite
```

Puis créez une Pull Request sur GitHub.

### Guidelines

- Respectez l'architecture MVC existante
- Commentez votre code si nécessaire
- Testez vos modifications
- Mettez à jour la documentation si besoin
- Suivez les standards PSR-12 pour PHP

## 🐛 Signaler un Bug

Utilisez l'[issue tracker GitHub](https://github.com/Toavina-Andria/BNGRC/issues) pour signaler des bugs.

**Template de bug report** :

```markdown
**Description**
Description claire du bug

**Reproduction**
Étapes pour reproduire le comportement

**Comportement attendu**
Ce qui devrait se passer

**Captures d'écran**
Si applicable

**Environnement**
- OS: [ex: Ubuntu 20.04]
- PHP: [ex: 8.1]
- MySQL: [ex: 8.0]
- Navigateur: [ex: Chrome 120]
```

## 📜 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👥 Auteurs

- **Toavina Andria** - *Développeur Principal* - [@Toavina-Andria](https://github.com/Toavina-Andria)

## 🙏 Remerciements

- [Flight PHP](https://flightphp.com/) - Framework micro PHP
- [Flexy Bootstrap](https://www.wrappixel.com/) - Template d'administration
- [Tracy Debugger](https://tracy.nette.org/) - Excellent outil de debug
- [Tabler Icons](https://tabler-icons.io/) - Icônes modernes
- BNGRC - Pour le soutien du projet

---

<div align="center">

**[⬆ Retour en haut](#-bngrc---système-de-gestion-des-sinistres)**

Made with ❤️ pour Madagascar

</div>
