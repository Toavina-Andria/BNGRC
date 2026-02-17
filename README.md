# Système de Gestion BNGRC (Bureau National de Gestion des Risques et Catastrophes)

## 📋 Description du Projet

Ce projet est une application web de gestion des catastrophes naturelles et des besoins humanitaires. Il permet de gérer:
- **Les sinistres** survenus dans différentes villes
- **Les besoins** (en argent et en nature) liés aux sinistres
- **Les dons** reçus pour venir en aide aux sinistrés
- **Les achats** effectués pour combler les manques
- **La distribution automatique** des ressources disponibles

Le système propose également un tableau de bord complet avec des statistiques et une récapitulation en temps réel.

## 🛠 Technologies Utilisées

- **PHP 7.4+** / **PHP 8.0+**
- **Flight PHP Framework** (v3.0+) - Micro-framework REST léger
- **PDO** pour les interactions avec la base de données
- **Tracy** pour le débogage
- **Bootstrap 5** pour l'interface utilisateur
- **JavaScript** (vanilla) pour l'interactivité
- **Vagrant** pour l'environnement de développement

## 📁 Architecture du Projet

Le projet suit une architecture **MVC (Model-View-Controller)** organisée selon ce pattern:

```
BNGRC/
├── app/                          # Code source de l'application
│   ├── cache/                    # Cache de l'application
│   ├── commands/                 # Commandes CLI (Runway)
│   ├── config/                   # Configuration de l'application
│   │   ├── bootstrap.php         # Point d'entrée de l'app
│   │   ├── config.php            # Configuration générale
│   │   ├── routes.php            # Définition des routes
│   │   └── services.php          # Enregistrement des services
│   ├── controllers/              # Contrôleurs MVC
│   │   ├── AchatController.php
│   │   ├── BesoinController.php
│   │   ├── DashboardController.php
│   │   ├── DonController.php
│   │   ├── InsertionController.php
│   │   ├── SinistreController.php
│   │   └── VilleController.php
│   ├── log/                      # Logs de l'application
│   ├── middlewares/              # Middlewares HTTP
│   │   └── SecurityHeadersMiddleware.php
│   ├── models/                   # Modèles de données (Active Record)
│   │   ├── Achat.php
│   │   ├── Categorie.php
│   │   ├── CategorieBesoin.php
│   │   ├── Data.php
│   │   ├── Don.php
│   │   ├── Region.php
│   │   ├── Sinistre.php
│   │   ├── SinistreBesoin.php
│   │   └── Ville.php
│   ├── services/                 # Couche service (logique métier)
│   │   ├── AchatService.php
│   │   ├── DashboardService.php
│   │   ├── DispatcherService.php
│   │   ├── DonService.php
│   │   └── SinistreService.php
│   ├── utils/                    # Utilitaires
│   │   └── Validator.php
│   └── views/                    # Vues (templates PHP)
│       ├── achat/
│       ├── dashboard/
│       ├── don/
│       ├── sinistre/
│       └── ville/
├── public/                       # Dossier public accessible par le web
│   ├── index.php                 # Point d'entrée web
│   └── assets/                   # Ressources statiques (CSS, JS)
├── script-database/             # Scripts SQL de migration
├── vendor/                       # Dépendances Composer
├── composer.json                 # Configuration Composer
├── Vagrantfile                   # Configuration Vagrant
└── runway                        # CLI Runway

```

## 🎯 Fonctionnalités Principales

### 1. Dashboard (Page d'accueil)
- Vue d'ensemble avec statistiques:
  - Nombre total de sinistres
  - Nombre de villes affectées
  - Nombre de régions concernées
  - Total des besoins
- Liste des villes avec leurs besoins et dons reçus
- Totaux des dons par catégorie

### 2. Gestion des Sinistres
- **Liste** des sinistres enregistrés
- **Ajout** d'un nouveau sinistre (nombre de sinistrés, ville)
- **Ajout de besoins** liés à un sinistre (argent ou nature)

### 3. Gestion des Dons
- **Liste** de tous les dons reçus
- **Ajout** d'un don (argent ou en nature)
- **Simulation** de la distribution des dons
- **Dispatch automatique** des dons selon les besoins

### 4. Gestion des Achats
- **Liste** des achats effectués
- **Visualisation** des besoins restants
- **Formulaire** d'achat pour combler les manques
- Calcul automatique du montant selon quantité et prix unitaire

### 5. Récapitulation
- Vue consolidée de tous les besoins, dons et achats
- Actualisation automatique des données (AJAX)
- Calcul des manques et du total distribué

### 6. Gestion des Villes
- Vue détaillée des besoins par ville

### 7. Réinitialisation
- Fonction de réinitialisation des données avec confirmation

## 🚀 Installation et Lancement

### Prérequis
- PHP 7.4+ ou PHP 8.0+
- Composer
- Base de données PostgreSQL ou MySQL/MariaDB
- Serveur web (Apache, Nginx) ou serveur PHP intégré

### Installation

1. **Cloner le projet** (ou extraire l'archive)
```bash
cd /home/toavina/Documents/Prog_DIR/framework/BNGRC
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
   - Copier le fichier de configuration (si nécessaire):
   ```bash
   cp app/config/config_sample.php app/config/config.php
   ```
   - Éditer `app/config/config.php` avec vos paramètres de connexion DB

4. **Importer la base de données**
   - Utiliser le script SQL le plus récent dans `script-database/`:
   ```bash
   # Exemple avec PostgreSQL:
   psql -U username -d database_name -f script-database/2026-2-17_14:33-script-complet.sql
   ```

5. **Lancer le serveur**
   ```bash
   composer start
   # ou
   php -S localhost:8000 -t public
   ```

6. **Accéder à l'application**
   - Ouvrir un navigateur: `http://localhost:8000`

### Avec Vagrant (optionnel)
```bash
vagrant up
vagrant ssh
# puis suivre les étapes d'installation ci-dessus
```

## 🌐 Routes Principales

Toutes les routes sont protégées par le middleware `SecurityHeadersMiddleware`.

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/` | Dashboard principal |
| GET | `/reset` | Confirmation de réinitialisation |
| POST | `/reset` | Réinitialisation des données |
| GET | `/villes/besoins` | Besoins d'une ville spécifique |
| GET | `/besoins/liste` | Liste de tous les besoins |
| GET | `/dons/liste` | Liste de tous les dons |
| GET | `/dons/insert` | Formulaire d'ajout de don |
| POST | `/dons/insert` | Traitement ajout de don |
| GET | `/dons/simuler` | Simulation de distribution |
| GET | `/dons/dispatch` | Lancement de la distribution |
| GET | `/achats/liste` | Liste des achats |
| GET | `/achats/besoins-restants` | Besoins non couverts |
| GET | `/achats/form` | Formulaire d'achat |
| POST | `/achats/insert` | Traitement achat |
| GET | `/recapitulation/` | Page de récapitulation |
| GET | `/recapitulation/ajax` | API Ajax (JSON) |
| GET | `/sinistres/liste` | Liste des sinistres |
| GET | `/sinistres/insert` | Formulaire de sinistre |
| POST | `/sinistres/insert` | Traitement sinistre |
| GET | `/sinistres/besoins/insert` | Formulaire de besoin |
| POST | `/sinistres/besoins/insert` | Traitement besoin |

## 🗄 Structure de la Base de Données

### Tables Principales

- **`bn_sinistre`** : Sinistres enregistrés
- **`bn_ville`** : Villes et localités
- **`bn_region`** : Régions géographiques
- **`bn_categorie`** : Catégories de dons/besoins (argent, vivres, etc.)
- **`bn_categorie_besoin`** : Sous-catégories de besoins
- **`bn_don`** : Dons reçus
- **`bn_achat`** : Achats effectués
- **`bn_sinistre_besoin`** : Besoins liés aux sinistres

### Vues (Views)

Le système utilise des vues SQL pour faciliter les requêtes complexes. Consultez les scripts dans `script-database/` pour plus de détails.

## 💡 Points d'Attention pour l'Évaluation

### 1. **Architecture MVC**
Le projet respecte une séparation claire des responsabilités:
- **Models** : Accès aux données (CRUD basique)
- **Controllers** : Orchestration des requêtes HTTP
- **Services** : Logique métier complexe (calculs, distributions)
- **Views** : Présentation des données

### 2. **Pattern Service Layer**
La logique métier complexe est isolée dans des services:
- `DispatcherService` : Algorithme de distribution des dons
- `DashboardService` : Agrégation des données pour le dashboard
- `SinistreService`, `DonService`, `AchatService` : Opérations métier

### 3. **Validation**
La classe `Validator` dans `app/utils/` centralise les règles de validation.

### 4. **Middleware**
`SecurityHeadersMiddleware` ajoute des en-têtes de sécurité HTTP à toutes les réponses.

### 5. **CLI Commands**
Le projet intègre Runway qui permet de créer des commandes CLI (voir `app/commands/`).

### 6. **Gestion des Erreurs**
Tracy est configuré pour le débogage en développement.

## 🔧 Configuration

Le fichier principal de configuration est `app/config/config.php`. Il contient:
- Paramètres de connexion à la base de données
- Configuration du framework Flight
- Timezone et locale
- Niveau d'erreurs

## 📝 Schéma de Fonctionnement

### Flux de Distribution des Dons

1. **Enregistrement des sinistres** avec leurs besoins
2. **Réception des dons** (argent ou nature)
3. **Simulation** : Prévisualisation de la distribution
4. **Dispatch** : Distribution automatique selon un algorithme
   - Priorise les besoins urgents
   - Distribue équitablement les ressources
   - Met à jour les tables `bn_don` et crée les liens
5. **Analyse des manques** : Identification des besoins non couverts
6. **Achats** : Compléter avec des achats si nécessaire

### Flux de Données

```
Utilisateur
    ↓
Routes (routes.php)
    ↓
Controller (ex: DonController)
    ↓
Service (ex: DonService)
    ↓
Model (ex: Don)
    ↓
Base de Données
    ↓
View (templates PHP)
    ↓
Navigateur
```

## 🎨 Interface Utilisateur

L'interface utilise **Bootstrap 5** pour un design responsive et moderne. Les vues sont des templates PHP dans `app/views/` avec:
- Tables pour les listes
- Formulaires avec validation côté client
- Alerts pour les messages de succès/erreur
- Cards pour les statistiques
- Actualisation AJAX sur la page de récapitulation

## 📚 Dépendances Composer

- `flightphp/core` : Framework principal
- `flightphp/runway` : CLI pour commandes personnalisées
- `tracy/tracy` : Barre de débogage
- `flightphp/tracy-extensions` : Intégration Tracy avec Flight

## 🔍 Débogage

### Tracy Debug Bar
Tracy est activé en développement. Pour voir la barre de débogage:
- Accédez à n'importe quelle page
- La barre apparaît en bas de l'écran
- Cliquez sur les onglets pour voir les requêtes SQL, variables, etc.

### Fichiers de Debug
- `public/debug.php` : Tests divers
- `public/check.php` : Vérifications de configuration
- `public/test.php` : Tests unitaires basiques
- `public/url-debug.php` : Debug des URLs/routes

## 📖 Ressources Additionnelles

- **Documentation Flight PHP** : https://docs.flightphp.com
- **Bootstrap 5** : https://getbootstrap.com
- **Tracy** : https://tracy.nette.org

## 🤝 Contribution

Ce projet a été développé dans un cadre pédagogique. Les améliorations possibles incluent:
- Tests unitaires automatisés (PHPUnit)
- API REST pour applications mobiles
- Authentification et gestion des utilisateurs
- Export des données (PDF, Excel)
- Graphiques et visualisations avancées
- Internationalisation (i18n)
- Notifications par email/SMS
- Module de reporting avancé

---

**Développé avec Flight PHP Framework** ✈️

*Ce projet démontre la maîtrise de l'architecture MVC, la séparation des préoccupations, l'utilisation d'un micro-framework moderne, et la gestion complète d'un système CRUD avec logique métier complexe.*
