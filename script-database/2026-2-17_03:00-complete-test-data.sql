-- Complete test data for BNGRC database with new donation system
-- Date: 17 février 2026, 03:00
-- This data follows the latest schema with prix_unitaire support

USE bngrc;

-- Clear existing data (in correct order for foreign keys)
DELETE FROM bn_achat;
DELETE FROM bn_don_nature;
DELETE FROM bn_don_argent;
DELETE FROM bn_don;
DELETE FROM bn_sinistre_besoin;
DELETE FROM bn_categorie_besoin;
DELETE FROM bn_sinistre;
DELETE FROM bn_ville;
DELETE FROM bn_region;

-- Regions
INSERT INTO bn_region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava'),
('Sofia'),
('Boeny'),
('Atsimo-Atsinanana'),
('Haute Matsiatra');

-- Villes avec population
INSERT INTO bn_ville (nom, population, id_region) VALUES
('Antananarivo', 1400000, 1),
('Antsirabe', 257000, 2),
('Miarinarivo', 200000, 3),
('Tsiroanomandidy', 26000, 4),
('Antsohihy', 37000, 5),
('Mahajanga', 273000, 6),
('Farafangana', 28000, 7),
('Fianarantsoa', 190000, 8);

-- Disasters (Sinistres)
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES
(150, 1),  -- Cyclone à Antananarivo
(80, 2),   -- Inondation à Antsirabe
(45, 3),   -- Sécheresse à Miarinarivo
(25, 4),   -- Glissement de terrain à Tsiroanomandidy
(120, 6),  -- Cyclone à Mahajanga
(60, 7),   -- Inondation à Farafangana
(35, 8);   -- Incendie à Fianarantsoa

-- Categories de besoins
INSERT INTO bn_categorie_besoin (nom) VALUES
('Nourriture'),
('Eau'),
('Abri'),
('Médicaments'),
('Vêtements'),
('Hygiène'),
('Outils'),
('Éducation');

-- Besoins des sinistres avec prix unitaires réalistes
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES
-- Sinistre 1: Antananarivo (Cyclone)
(1, 1, 'Sacs de riz 25kg', 5000, 45000.00),
(1, 2, 'Bidons d\'eau 20L', 10000, 5000.00),
(1, 3, 'Tentes familiales 6 personnes', 200, 350000.00),
(1, 4, 'Kits de premiers secours', 300, 25000.00),
(1, 5, 'Couvertures et vêtements chauds', 1000, 15000.00),
(1, 6, 'Kits d\'hygiène (savon, dentifrice)', 800, 8000.00),

-- Sinistre 2: Antsirabe (Inondation)
(2, 1, 'Conserves alimentaires variées', 2000, 12000.00),
(2, 2, 'Comprimés de purification d\'eau', 4000, 2000.00),
(2, 3, 'Bâches imperméables', 100, 45000.00),
(2, 4, 'Médicaments de base', 150, 30000.00),
(2, 6, 'Produits d\'hygiène', 200, 10000.00),

-- Sinistre 3: Miarinarivo (Sécheresse)
(3, 1, 'Aide alimentaire d\'urgence', 1500, 35000.00),
(3, 2, 'Citernes d\'eau 1000L', 50, 450000.00),
(3, 6, 'Kits d\'assainissement', 400, 12000.00),
(3, 7, 'Outils agricoles', 200, 25000.00),

-- Sinistre 4: Tsiroanomandidy (Glissement)
(4, 3, 'Matériaux de reconstruction', 50, 500000.00),
(4, 1, 'Rations alimentaires d\'urgence', 500, 20000.00),
(4, 4, 'Matériel médical d\'urgence', 75, 40000.00),

-- Sinistre 5: Mahajanga (Cyclone)
(5, 1, 'Nourriture non périssable', 3000, 30000.00),
(5, 2, 'Eau potable en bouteille', 8000, 3000.00),
(5, 3, 'Kits d\'abri d\'urgence', 150, 280000.00),
(5, 4, 'Trousses médicales complètes', 200, 35000.00),
(5, 5, 'Vêtements pour enfants', 800, 18000.00),

-- Sinistre 6: Farafangana (Inondation)
(6, 1, 'Riz et haricots', 1200, 38000.00),
(6, 2, 'Filtres à eau portables', 300, 85000.00),
(6, 6, 'Produits sanitaires', 500, 9000.00),

-- Sinistre 7: Fianarantsoa (Incendie)
(7, 3, 'Tentes et abris temporaires', 80, 320000.00),
(7, 5, 'Vêtements complets', 600, 22000.00),
(7, 8, 'Fournitures scolaires', 400, 15000.00);

-- ========================================
-- DONS EN ARGENT (Money Donations)
-- ========================================
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES
('argent', 'Fondation Ravinala', '2026-02-15 09:00:00', 1),
('argent', 'Entreprise TelMa', '2026-02-15 10:30:00', 1),
('argent', 'Banque BNI Madagascar', '2026-02-15 14:00:00', 2),
('argent', 'Donateur Anonyme A', '2026-02-16 08:00:00', NULL),
('argent', 'Association Mada-Aid', '2026-02-16 11:00:00', 6),
('argent', 'Orange Madagascar', '2026-02-16 15:30:00', 1),
('argent', 'Groupe Filatex', '2026-02-16 16:00:00', 8),
('argent', 'Croix-Rouge Malgache', '2026-02-17 09:00:00', NULL);

INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES
(1, 15000000.00, 15000000.00),   -- 15M MGA
(2, 25000000.00, 25000000.00),   -- 25M MGA
(3, 10000000.00, 10000000.00),   -- 10M MGA
(4, 50000000.00, 50000000.00),   -- 50M MGA (gros donateur)
(5, 8000000.00, 8000000.00),     -- 8M MGA
(6, 20000000.00, 20000000.00),   -- 20M MGA
(7, 12000000.00, 12000000.00),   -- 12M MGA
(8, 30000000.00, 30000000.00);   -- 30M MGA
-- Total: 170M MGA disponible

-- ========================================
-- DONS EN NATURE (In-kind Donations)
-- ========================================
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES
('nature', 'PAM (Programme Alimentaire Mondial)', '2026-02-15 11:00:00', 1),
('nature', 'UNICEF Madagascar', '2026-02-15 13:00:00', 2),
('nature', 'ONG Médecins du Monde', '2026-02-15 15:00:00', 1),
('nature', 'Collecte Communautaire Antsirabe', '2026-02-16 09:00:00', 2),
('nature', 'Église Adventiste FJKM', '2026-02-16 10:00:00', 1),
('nature', 'Rotary Club Madagascar', '2026-02-16 12:00:00', 6),
('nature', 'Association Scouts', '2026-02-16 14:00:00', NULL),
('nature', 'Donateur Anonyme B', '2026-02-17 10:00:00', NULL);

-- Don 9: PAM

-- Don 10: UNICEF
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES
(9, 1, 'Sacs de riz 25kg - donation internationale', 2000),
(9, 2, 'Bidons d\'eau 20L', 5000),
(10, 2, 'Comprimés purification d\'eau', 10000),
(10, 6, 'Kits d\'hygiène pour enfants', 1500),
(10, 8, 'Fournitures scolaires complètes', 800),
(11, 4, 'Kits médicaux complets', 250),
(11, 4, 'Médicaments essentiels', 500),
(12, 1, 'Conserves alimentaires', 800),
(12, 5, 'Vêtements pour adultes', 600),
(13, 1, 'Rations alimentaires', 1000),
(13, 6, 'Produits d\'hygiène', 500),
(13, 5, 'Couvertures', 400),
(14, 3, 'Tentes 4 personnes', 50),
(14, 7, 'Outils de reconstruction', 100),
(15, 6, 'Kits hygiène familiale', 300),
(15, 5, 'Vêtements pour enfants', 450),
(16, 2, 'Filtres à eau portables', 150),
(16, 3, 'Bâches imperméables 6x4m', 80);

-- ========================================
-- ACHATS (Purchases using money donations)
-- ========================================

-- Achat 1: Acheter du riz avec don #1 (Fondation Ravinala)
-- Besoin: 5000 sacs à 45000 MGA = 225M (on achète 200 sacs)
-- Prix: 200 * 45000 = 9M, avec 12% frais = 10,080,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(1, 1, 200, 45000.00, 12.00, 10080000.00, '2026-02-16 10:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 10080000.00 WHERE id_don = 1;
UPDATE bn_sinistre_besoin SET quantite = quantite - 200 WHERE id = 1;

-- Achat 2: Acheter des tentes avec don #2 (TelMa)
-- Besoin: 200 tentes à 350000 MGA (on achète 50 tentes)
-- Prix: 50 * 350000 = 17.5M, avec 8% frais = 18,900,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(2, 3, 50, 350000.00, 8.00, 18900000.00, '2026-02-16 12:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 18900000.00 WHERE id_don = 2;
UPDATE bn_sinistre_besoin SET quantite = quantite - 50 WHERE id = 3;

-- Achat 3: Acheter des médicaments avec don #3 (BNI)
-- Besoin: 150 médicaments à 30000 MGA (on achète 100)
-- Prix: 100 * 30000 = 3M, avec 10% frais = 3,300,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(3, 10, 100, 30000.00, 10.00, 3300000.00, '2026-02-16 14:30:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 3300000.00 WHERE id_don = 3;
UPDATE bn_sinistre_besoin SET quantite = quantite - 100 WHERE id = 10;

-- Achat 4: Acheter de l'eau avec don #5 (Mada-Aid)
-- Besoin: 8000 bouteilles à 3000 MGA (on achète 2000)
-- Prix: 2000 * 3000 = 6M, avec 5% frais = 6,300,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(5, 24, 2000, 3000.00, 5.00, 6300000.00, '2026-02-16 16:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 6300000.00 WHERE id_don = 5;
UPDATE bn_sinistre_besoin SET quantite = quantite - 2000 WHERE id = 24;

-- Achat 5: Acheter des vêtements avec don #6 (Orange)
-- Besoin: 1000 vêtements à 15000 MGA (on achète 300)
-- Prix: 300 * 15000 = 4.5M, avec 7% frais = 4,815,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(6, 5, 300, 15000.00, 7.00, 4815000.00, '2026-02-17 09:30:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 4815000.00 WHERE id_don = 6;
UPDATE bn_sinistre_besoin SET quantite = quantite - 300 WHERE id = 5;

-- Achat 6: Acheter des kits d'hygiène avec don #7 (Filatex)
-- Besoin: 800 kits à 8000 MGA (on achète 500)
-- Prix: 500 * 8000 = 4M, avec 6% frais = 4,240,000 MGA
INSERT INTO bn_achat (id_don_argent, id_sinistre_besoin, quantite, prix_unitaire, frais_pourcentage, montant_total, date_achat) VALUES
(7, 6, 500, 8000.00, 6.00, 4240000.00, '2026-02-17 11:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 4240000.00 WHERE id_don = 7;
UPDATE bn_sinistre_besoin SET quantite = quantite - 500 WHERE id = 6;

-- ========================================
-- RÉSUMÉ DES DONNÉES
-- ========================================
-- Total dons en argent: 170,000,000 MGA
-- Total dépensé: 51,635,000 MGA
-- Solde restant: 118,365,000 MGA
-- 
-- Dons en nature: 16 items across 8 donations
-- Achats effectués: 6 purchases with fees ranging 5-12%
-- 
-- Sinistres: 7 disasters across 7 cities
-- Besoins: 31 different needs with realistic prices
