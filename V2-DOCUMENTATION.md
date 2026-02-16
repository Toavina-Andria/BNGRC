# BNGRC - Version 2 - Documentation

## Nouvelles Fonctionnalités Implémentées

### 1. Système de Validation des Données (Sécurité)

**Fichier créé :** `app/utils/Validator.php`

**Fonctionnalités :**
- Nettoyage des chaînes de caractères (suppression des balises HTML/PHP, caractères dangereux)
- Validation des montants positifs (> 0)
- Validation des quantités entières positives (> 0)
- Validation des IDs
- Protection contre les injections SQL et XSS

**Intégration :**
- ✅ `app/controllers/DonController.php` - validation des dons
- ✅ `app/controllers/InsertionController.php` - validation des sinistres et besoins
- ✅ `app/controllers/AchatController.php` - validation des achats

---

### 2. Système d'Achats via Dons en Argent

#### 2.1 Base de Données

**Script SQL :** `script-database/2026-2-17_v2-achats.sql`

**Tables créées :**
- `bn_achat` : Enregistrement des achats effectués
  - id_ville, id_besoin, id_don_argent
  - quantite, prix_unitaire, montant_total
  - frais_pourcentage, montant_avec_frais
  - date_achat

- `bn_config_achat` : Configuration des frais d'achat
  - frais_pourcentage (par défaut : 10%)

**Configuration :** `app/config/config.php`
```php
'bngrc' => [
    'frais_achat_pourcentage' => 10.00, // Frais d'achat en %
]
```

#### 2.2 Modèle et Contrôleur

**Fichiers créés :**
- `app/models/Achat.php` - Gestion des achats
  - `create()` - Créer un achat
  - `findAll()` - Liste des achats (filtrable par ville)
  - `besoinExisteDansDonsNature()` - Vérifier si besoin existe dans dons nature
  - `getFraisPourcentage()` - Obtenir le % de frais configuré
  - `calculerMontantAvecFrais()` - Calculer montant TTC

- `app/controllers/AchatController.php` - Gestion des achats
  - `showBesoinsRestants()` - Afficher besoins non satisfaits
  - `showAchatForm()` - Formulaire d'achat
  - `insertAchat()` - Traiter l'achat
  - `listeAchats()` - Liste des achats effectués

#### 2.3 Vues

**Fichiers créés :**
- `app/views/achat/besoins_restants.php` - Liste des besoins restants
  - Filtrable par ville
  - Affiche montant HT et TTC (avec frais)
  - Bouton "Acheter" pour chaque besoin

- `app/views/achat/form.php` - Formulaire d'achat
  - Affiche détails du besoin
  - Sélection du don en argent à utiliser
  - Calcul automatique des montants (base + frais)
  - Vérification suffisance du don
  - **Erreur si besoin existe déjà dans dons nature**

- `app/views/achat/liste.php` - Liste des achats effectués
  - Filtrable par ville
  - Affiche tous les détails : montant base, frais, total
  - Statistiques globales

#### 2.4 Logique Métier

**Règles implémentées :**
1. ❌ **Empêcher achat si besoin existe dans dons nature disponibles**
   - Message d'erreur : "Ce besoin existe déjà dans les dons en nature disponibles"
   - Redirection vers simulation de dispatch

2. ✅ **Calcul des frais d'achat**
   - Exemple : Achat de 100 Ar → 100 × 1.10 = 110 Ar (avec 10% frais)
   - `montant_avec_frais = montant_base × (1 + frais_pourcentage / 100)`

3. ✅ **Vérification du montant disponible**
   - Empêche achat si don en argent insuffisant
   - Mise à jour automatique du `montant_restant` après achat

4. ✅ **Mise à jour des besoins**
   - Réduction de la quantité du besoin après achat
   - Transaction DB pour garantir la cohérence

---

### 3. Système de Simulation et Validation du Dispatch

#### 3.1 Contrôleur

**Modifications :** `app/controllers/DonController.php`

**Méthodes ajoutées :**
- `simulateDispatch()` - Simuler le dispatch sans appliquer
  - Utilise une transaction rollback
  - Retourne les mêmes données que dispatch réel
  - Paramètre `simulation=true` passé à la vue

- `dispatchDons()` - Dispatch réel (existant)
  - Applique réellement les modifications
  - Paramètre `simulation=false` passé à la vue

#### 3.2 Service

**Modifications :** `app/services/DonService.php`

**Note importante :** 🚧 **LOGIQUE TEMPORAIRE - À AMÉLIORER**

La logique actuelle utilise une **distribution ALÉATOIRE** (`ORDER BY RAND()`)

**À implémenter plus tard :**
- Dispatch par ordre chronologique (date de réception)
- Priorisation selon la ville ciblée
- Matching dons nature ↔ besoins de même catégorie
- Utilisation intelligente des dons argent
- Calcul précis des montants et quantités

#### 3.3 Vue

**Modifications :** `app/views/don/dispatch.php`

**Fonctionnalités ajoutées :**
- Mode simulation vs validation
  - **Simulation** : Alerte bleue + bouton "Valider le Dispatch"
  - **Validation** : Alerte verte + boutons vers dashboard/récapitulation
- Affichage du résultat identique dans les deux modes
- Bouton "Annuler" en mode simulation

**Routes :**
- `/dons/simuler` → Mode simulation
- `/dons/dispatch` → Dispatch réel

---

### 4. Page de Récapitulation avec Ajax

#### 4.1 Contrôleur

**Modifications :** `app/controllers/DashboardController.php`

**Méthodes ajoutées :**
- `recapitulation()` - Afficher la page de récapitulation
- `recapitulationAjax()` - API Ajax pour actualiser les données
- `getRecapitulationData()` - Calculer toutes les statistiques

**Données calculées :**
- Besoins totaux (montant + quantité)
- Besoins satisfaits (montant + quantité)
- Besoins restants (montant + quantité)
- Taux de couverture (%)
- Répartition par catégorie
- Statistiques des dons (argent + nature)

#### 4.2 Vue

**Fichier créé :** `app/views/dashboard/recapitulation.php`

**Fonctionnalités :**
- 4 cartes de statistiques principales
  - Besoins totaux (bleu)
  - Besoins satisfaits (vert)
  - Besoins restants (rouge)
  - Taux de couverture avec barre de progression (bleu)

- Tableau détaillé par catégorie
  - Montant total / satisfait / restant par catégorie
  - Barre de progression colorée (vert ≥75%, jaune ≥50%, rouge <50%)

- Statistiques des dons
  - Nombre de dons argent / nature
  - Montant total argent
  - Montant restant argent

- **Bouton "Actualiser"** avec Ajax
  - Actualise toutes les données sans recharger la page
  - Affiche icône de chargement pendant la requête
  - Met à jour la date de dernière mise à jour

**Routes :**
- `/recapitulation` → Page de récapitulation
- `/recapitulation/ajax` → API Ajax (retourne JSON)

---

### 5. Navigation et Menu

**Modifications :** `app/views/dashboard/partial/header.php`

**Liens ajoutés :**
- **Dons** (`/dons/liste`) - Icône cadeau
- **Achats** (`/achats/besoins-restants`) - Icône panier
- **Récapitulation** (`/recapitulation`) - Icône graphique (section Rapports)

**Modifications :** `app/views/don/liste.php`

**Boutons ajoutés :**
- "Simuler dispatch" (`/dons/simuler`) - Bouton bleu
- Séparation visuelle entre simulation et dispatch réel

---

## Routes Complètes

### Dons
- `GET  /dons/liste` - Liste des dons
- `GET  /dons/insert` - Formulaire nouveau don
- `POST /dons/insert` - Insérer un don
- `GET  /dons/simuler` - **NOUVEAU** : Simuler le dispatch
- `GET  /dons/dispatch` - Dispatch réel

### Achats (NOUVEAU)
- `GET  /achats/besoins-restants` - Liste des besoins restants
- `GET  /achats/form?id_besoin=X` - Formulaire d'achat
- `POST /achats/insert` - Insérer un achat
- `GET  /achats/liste` - Liste des achats effectués

### Récapitulation (NOUVEAU)
- `GET  /recapitulation` - Page de récapitulation
- `GET  /recapitulation/ajax` - API Ajax (JSON)

---

## Installation et Migration

### 1. Appliquer les migrations SQL

```bash
# Créer la table bn_achat et la configuration
mysql -u root -p bngrc < script-database/2026-2-17_v2-achats.sql
```

### 2. Vérifier la configuration

Fichier `app/config/config.php` :
```php
'bngrc' => [
    'frais_achat_pourcentage' => 10.00, // Modifier si nécessaire
],
```

### 3. Tester les fonctionnalités

1. **Achats** :
   - Aller sur `/achats/besoins-restants`
   - Sélectionner un besoin
   - Vérifier le calcul automatique avec frais
   - Tenter d'acheter un besoin qui existe dans dons nature → erreur attendue

2. **Simulation** :
   - Aller sur `/dons/liste`
   - Cliquer sur "Simuler dispatch"
   - Vérifier l'alerte bleue et le bouton "Valider"
   - Cliquer sur "Valider" → dispatch réel

3. **Récapitulation** :
   - Aller sur `/recapitulation`
   - Vérifier l'affichage des statistiques
   - Cliquer sur "Actualiser" → données mises à jour en Ajax

---

## Points d'Attention

### ⚠️ Logique Temporaire

La logique de dispatch actuelle est **ALÉATOIRE** et doit être améliorée :

```php
// DANS DonService.php
// TODO FUTUR : LOGIQUE DE DISPATCH TEMPORAIRE - À AMÉLIORER
// ACTUELLEMENT : Distribution ALÉATOIRE des dons
// À IMPLÉMENTER PLUS TARD : Dispatch chronologique, priorisation, matching intelligent
```

**Commentaires dans le code :**
- `app/services/DonService.php` - Gros blocs TODO avec détails de ce qu'il faut implémenter

### ✅ Sécurité

Toutes les entrées utilisateur sont validées via `Validator::sanitizeString()` et `Validator::validatePositiveAmount()`

### ✅ Transactions DB

Tous les achats et dispatches utilisent des transactions pour garantir la cohérence des données

### ✅ Messages d'Erreur

Messages clairs pour les utilisateurs :
- Besoin existe dans dons nature → proposer simulation
- Don en argent insuffisant → montant requis vs disponible affiché
- Quantité supérieure au besoin → limitation automatique

---

## Prochaines Étapes (Hors V2)

- [ ] Implémenter la vraie logique de dispatch (chronologique + matching)
- [ ] Ajouter filtres avancés dans les listes (dates, catégories)
- [ ] Exporter les rapports en PDF/Excel
- [ ] Notifications en temps réel
- [ ] Historique des modifications
- [ ] Gestion des utilisateurs et permissions

---

**Date de création :** 2026-02-17  
**Version :** 2.0  
**Auteurs :** Mamy Aiky Rakotomalala, Toavina Andriamonta, Nekena Manovosoa
