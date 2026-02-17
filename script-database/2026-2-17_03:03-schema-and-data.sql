-- Active: 1770642207821@@127.0.0.1@3306@bngrc
-- ====================================================================
-- BNGRC DATABASE - FINAL SCHEMA AND TEST DATA
-- ====================================================================
-- Date: 2026-02-17 04:00
-- Description: Complete database setup with schema and realistic test data
-- For disaster and donation management system
-- ====================================================================

CREATE DATABASE IF NOT EXISTS bngrc;
USE bngrc;

-- ====================================================================
-- DROP EXISTING TABLES (in correct order for foreign keys)
-- ====================================================================

DROP TABLE IF EXISTS bn_achat;
DROP TABLE IF EXISTS bn_don_nature;
DROP TABLE IF EXISTS bn_don_argent;
DROP TABLE IF EXISTS bn_don;
DROP TABLE IF EXISTS bn_sinistre_besoin;
DROP TABLE IF EXISTS bn_categorie_besoin;
DROP TABLE IF EXISTS bn_sinistre;
DROP TABLE IF EXISTS bn_ville;
DROP TABLE IF EXISTS bn_region;
DROP TABLE IF EXISTS bn_config_achat;
DROP TABLE IF EXISTS bn_config_don;

-- ====================================================================
-- CREATE TABLES
-- ====================================================================

-- Table des régions
CREATE TABLE bn_region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

-- Table des villes
CREATE TABLE bn_ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    population INT NOT NULL,
    id_region INT,
    FOREIGN KEY (id_region) REFERENCES bn_region(id)
);

-- Table des sinistres
CREATE TABLE bn_sinistre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_sinistres INT NOT NULL,
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES bn_ville(id)
);

-- Table des catégories de besoins
CREATE TABLE bn_categorie_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

-- Table des besoins des sinistres
CREATE TABLE bn_sinistre_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sinistre INT,
    id_categorie_besoin INT,
    description VARCHAR(255),
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2),
    FOREIGN KEY (id_sinistre) REFERENCES bn_sinistre(id),
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id)
);

-- Table des dons (principale)
CREATE TABLE bn_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('argent', 'nature') NOT NULL,
    donateur VARCHAR(100),
    date_don DATETIME NOT NULL,
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES bn_ville(id)
);

-- Table des dons en argent
CREATE TABLE bn_don_argent (
    id_don INT PRIMARY KEY,
    montant DECIMAL(12,2) NOT NULL,
    montant_restant DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_don) REFERENCES bn_don(id)
);

-- Table des dons en nature
CREATE TABLE bn_don_nature (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_don INT,
    id_categorie_besoin INT,
    description VARCHAR(255),
    quantite INT NOT NULL,
    FOREIGN KEY (id_don) REFERENCES bn_don(id),
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id)
);

-- Table des achats (utilisation d'argent pour acheter des besoins)
CREATE TABLE bn_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    id_don_argent INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    frais_pourcentage DECIMAL(5,2) NOT NULL DEFAULT 0,
    montant_avec_frais DECIMAL(10,2) NOT NULL,
    date_achat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bn_ville(id) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES bn_sinistre_besoin(id) ON DELETE CASCADE,
    FOREIGN KEY (id_don_argent) REFERENCES bn_don(id) ON DELETE CASCADE,
    INDEX idx_ville (id_ville),
    INDEX idx_date (date_achat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configuration des frais d'achat
CREATE TABLE bn_config_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_pourcentage DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    date_modification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO bn_config_achat (frais_pourcentage) VALUES (10.00);


-- ====================================================================
-- INSERT TEST DATA
-- ====================================================================

-- Régions
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

-- Sinistres (disasters)
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES
(150, 1),
(80, 2),
(45, 3),
(25, 4),
(120, 6),
(60, 7),
(35, 8);

-- Catégories de besoins
INSERT INTO bn_categorie_besoin (nom) VALUES
('Nourriture'),
('Eau'),
('Abri'),
('Médicaments'),
('Vêtements'),
('Hygiène'),
('Outils'),
('Éducation');

-- Besoins des sinistres avec prix unitaires
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES
(1, 1, 'Sacs de riz 25kg', 5000, 45000.00),
(1, 2, 'Bidons d\'eau 20L', 10000, 5000.00),
(1, 3, 'Tentes familiales 6 personnes', 200, 350000.00),
(1, 4, 'Kits de premiers secours', 300, 25000.00),
(1, 5, 'Couvertures et vêtements chauds', 1000, 15000.00),
(1, 6, 'Kits d\'hygiène (savon, dentifrice)', 800, 8000.00),
(2, 1, 'Conserves alimentaires variées', 2000, 12000.00),
(2, 2, 'Comprimés de purification d\'eau', 4000, 2000.00),
(2, 3, 'Bâches imperméables', 100, 45000.00),
(2, 4, 'Médicaments de base', 150, 30000.00),
(2, 6, 'Produits d\'hygiène', 200, 10000.00),
(3, 1, 'Aide alimentaire d\'urgence', 1500, 35000.00),
(3, 2, 'Citernes d\'eau 1000L', 50, 450000.00),
(3, 6, 'Kits d\'assainissement', 400, 12000.00),
(3, 7, 'Outils agricoles', 200, 25000.00),
(4, 3, 'Matériaux de reconstruction', 50, 500000.00),
(4, 1, 'Rations alimentaires d\'urgence', 500, 20000.00),
(4, 4, 'Matériel médical d\'urgence', 75, 40000.00),
(5, 1, 'Nourriture non périssable', 3000, 30000.00),
(5, 2, 'Eau potable en bouteille', 8000, 3000.00),
(5, 3, 'Kits d\'abri d\'urgence', 150, 280000.00),
(5, 4, 'Trousses médicales complètes', 200, 35000.00),
(5, 5, 'Vêtements pour enfants', 800, 18000.00),
(6, 1, 'Riz et haricots', 1200, 38000.00),
(6, 2, 'Filtres à eau portables', 300, 85000.00),
(6, 6, 'Produits sanitaires', 500, 9000.00),
(7, 3, 'Tentes et abris temporaires', 80, 320000.00),
(7, 5, 'Vêtements complets', 600, 22000.00),
(7, 8, 'Fournitures scolaires', 400, 15000.00);

-- ====================================================================
-- DONS EN ARGENT (Money Donations)
-- ====================================================================

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
(1, 15000000.00, 15000000.00),
(2, 25000000.00, 25000000.00),
(3, 10000000.00, 10000000.00),
(4, 50000000.00, 50000000.00),
(5, 8000000.00, 8000000.00),
(6, 20000000.00, 20000000.00),
(7, 12000000.00, 12000000.00),
(8, 30000000.00, 30000000.00);

-- ====================================================================
-- DONS EN NATURE (In-kind Donations)
-- ====================================================================

INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES
('nature', 'PAM (Programme Alimentaire Mondial)', '2026-02-15 11:00:00', 1),
('nature', 'UNICEF Madagascar', '2026-02-15 13:00:00', 2),
('nature', 'ONG Médecins du Monde', '2026-02-15 15:00:00', 1),
('nature', 'Collecte Communautaire Antsirabe', '2026-02-16 09:00:00', 2),
('nature', 'Église Adventiste FJKM', '2026-02-16 10:00:00', 1),
('nature', 'Rotary Club Madagascar', '2026-02-16 12:00:00', 6),
('nature', 'Association Scouts', '2026-02-16 14:00:00', NULL),
('nature', 'Donateur Anonyme B', '2026-02-17 10:00:00', NULL);

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

-- ====================================================================
-- ACHATS (Purchases using money donations)
-- ====================================================================

-- Achat 1: Acheter du riz avec don Fondation Ravinala
-- 200 sacs à 45000 MGA = 9M, avec 12% frais = 10,080,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (1, 1, 1, 200, 45000.00, 9000000.00, 12.00, 10080000.00, '2026-02-16 10:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 10080000.00 WHERE id_don = 1;
UPDATE bn_sinistre_besoin SET quantite = quantite - 200 WHERE id = 1;

-- Achat 2: Acheter des tentes avec don TelMa
-- 50 tentes à 350000 MGA = 17.5M, avec 8% frais = 18,900,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (1, 3, 2, 50, 350000.00, 17500000.00, 8.00, 18900000.00, '2026-02-16 12:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 18900000.00 WHERE id_don = 2;
UPDATE bn_sinistre_besoin SET quantite = quantite - 50 WHERE id = 3;

-- Achat 3: Acheter des médicaments avec don BNI
-- 100 médicaments à 30000 MGA = 3M, avec 10% frais = 3,300,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (2, 10, 3, 100, 30000.00, 3000000.00, 10.00, 3300000.00, '2026-02-16 14:30:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 3300000.00 WHERE id_don = 3;
UPDATE bn_sinistre_besoin SET quantite = quantite - 100 WHERE id = 10;

-- Achat 4: Acheter de l'eau avec don Mada-Aid
-- 2000 bouteilles à 3000 MGA = 6M, avec 5% frais = 6,300,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (6, 20, 5, 2000, 3000.00, 6000000.00, 5.00, 6300000.00, '2026-02-16 16:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 6300000.00 WHERE id_don = 5;
UPDATE bn_sinistre_besoin SET quantite = quantite - 2000 WHERE id = 20;

-- Achat 5: Acheter des vêtements avec don Orange
-- 300 vêtements à 15000 MGA = 4.5M, avec 7% frais = 4,815,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (1, 5, 6, 300, 15000.00, 4500000.00, 7.00, 4815000.00, '2026-02-17 09:30:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 4815000.00 WHERE id_don = 6;
UPDATE bn_sinistre_besoin SET quantite = quantite - 300 WHERE id = 5;

-- Achat 6: Acheter des kits d'hygiène avec don Filatex
-- 500 kits à 8000 MGA = 4M, avec 6% frais = 4,240,000 MGA
INSERT INTO bn_achat (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais, date_achat) 
VALUES (1, 6, 7, 500, 8000.00, 4000000.00, 6.00, 4240000.00, '2026-02-17 11:00:00');

UPDATE bn_don_argent SET montant_restant = montant_restant - 4240000.00 WHERE id_don = 7;
UPDATE bn_sinistre_besoin SET quantite = quantite - 500 WHERE id = 6;



-- ====================================================================
-- views
-- ====================================================================
-- Vue pour afficher les sinistres avec détails 
CREATE OR REPLACE VIEW v_sinistre_details AS
SELECT
    s.id AS sinistre_id,
    s.nombre_sinistres,
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.population AS ville_population,
    r.id AS region_id,
    r.nom AS region_nom,
    sb.id AS besoin_id,
    sb.id_categorie_besoin,
    cb.nom AS categorie_nom,
    sb.description AS besoin_description,
    sb.quantite AS besoin_quantite,
    sb.prix_unitaire,
    (sb.quantite * sb.prix_unitaire) AS montant_besoin
FROM bn_sinistre s
JOIN bn_ville v ON s.id_ville = v.id
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
LEFT JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id;

-- Vue pour les besoins par ville (agrégé)
CREATE OR REPLACE VIEW v_besoins_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.population,
    r.id AS region_id,
    r.nom AS region_nom,
    COUNT(DISTINCT s.id) AS nb_sinistres,
    SUM(s.nombre_sinistres) AS total_sinistres,
    COUNT(sb.id) AS nb_types_besoins,
    SUM(sb.quantite) AS total_quantite_besoins,
    SUM(sb.quantite * sb.prix_unitaire) AS montant_total_besoins
FROM bn_ville v
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre s ON v.id = s.id_ville
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
GROUP BY v.id, v.nom, v.population, r.id, r.nom;

-- Vue pour les dons par ville
CREATE OR REPLACE VIEW v_dons_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    d.id AS don_id,
    d.type AS don_type,
    d.donateur,
    d.date_don,
    CASE 
        WHEN d.type = 'argent' THEN da.montant_restant
        ELSE NULL 
    END AS montant_argent,
    dn.id_categorie_besoin,
    cb.nom AS categorie_nom,
    dn.description AS don_description,
    dn.quantite AS don_quantite
FROM bn_ville v
LEFT JOIN bn_don d ON v.id = d.id_ville
LEFT JOIN bn_don_argent da ON d.id = da.id_don
LEFT JOIN bn_don_nature dn ON d.id = dn.id_don
LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
WHERE d.id IS NOT NULL;

-- Vue agrégée des dons par ville
CREATE OR REPLACE VIEW v_total_dons_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    COUNT(DISTINCT d.id) AS nb_dons,
    SUM(CASE WHEN d.type = 'argent' THEN da.montant_restant ELSE 0 END) AS total_argent_disponible,
    COUNT(CASE WHEN d.type = 'nature' THEN 1 END) AS nb_dons_nature
FROM bn_ville v
LEFT JOIN bn_don d ON v.id = d.id_ville
LEFT JOIN bn_don_argent da ON d.id = da.id_don AND d.type = 'argent'
GROUP BY v.id, v.nom;

-- Vue principale pour le dashboard : ville avec besoins et dons
CREATE OR REPLACE VIEW v_dashboard_ville AS
SELECT 
    bv.ville_id,
    bv.ville_nom,
    bv.population,
    bv.region_id,
    bv.region_nom,
    bv.nb_sinistres,
    bv.total_sinistres,
    bv.nb_types_besoins,
    bv.total_quantite_besoins,
    bv.montant_total_besoins,
    COALESCE(dv.nb_dons, 0) AS nb_dons,
    COALESCE(dv.total_argent_disponible, 0) AS total_argent_disponible,
    COALESCE(dv.nb_dons_nature, 0) AS nb_dons_nature,
    CASE 
        WHEN bv.montant_total_besoins > 0 THEN 
            ROUND((COALESCE(dv.total_argent_disponible, 0) / bv.montant_total_besoins) * 100, 2)
        ELSE 0 
    END AS pourcentage_couverture
FROM v_besoins_par_ville bv
LEFT JOIN v_total_dons_par_ville dv ON bv.ville_id = dv.ville_id
WHERE bv.nb_sinistres > 0
ORDER BY bv.montant_total_besoins DESC;

-- ====================================================================
-- END OF SCRIPT
-- ====================================================================
-- Summary:
-- - 8 regions, 8 cities
-- - 7 disasters across different cities
-- - 8 need categories, 29 specific needs with prices
-- - 8 money donations: 170,000,000 MGA total
-- - 8 in-kind donations: 16 different items
-- - 6 purchases: 51,635,000 MGA spent
-- - Remaining balance: 118,365,000 MGA
-- ====================================================================
