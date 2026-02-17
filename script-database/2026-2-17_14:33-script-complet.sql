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

-- INSERT DATA
INSERT INTO bn_region (nom) VALUES ('Vatovavy');
INSERT INTO bn_region (nom) VALUES ('Atsimo-Atsinanana');
INSERT INTO bn_region (nom) VALUES ('Atsinanana');
INSERT INTO bn_region (nom) VALUES ('Diana');
INSERT INTO bn_region (nom) VALUES ('Menabe');
INSERT INTO bn_ville (nom, population, id_region) VALUES ('Toamasina', 326286, 3);
INSERT INTO bn_ville (nom, population, id_region) VALUES ('Mananjary', 30000, 1);
INSERT INTO bn_ville (nom, population, id_region) VALUES ('Farafangana', 24000, 2);
INSERT INTO bn_ville (nom, population, id_region) VALUES ('Nosy Be', 73000, 4);
INSERT INTO bn_ville (nom, population, id_region) VALUES ('Morondava', 70000, 5);
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (1200, 1);
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (600, 2);
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (800, 3);
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (400, 4);
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (1000, 5);
INSERT INTO bn_categorie_besoin (nom) VALUES ('nature');
INSERT INTO bn_categorie_besoin (nom) VALUES ('materiel');
INSERT INTO bn_categorie_besoin (nom) VALUES ('argent');
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 1, 'Riz (kg)', 800, 3000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 1, 'Eau (L)', 1500, 1000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 2, 'Tôle', 120, 25000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 2, 'Bâche', 200, 15000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 3, 'Argent', 12000000, 1);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (2, 1, 'Riz (kg)', 500, 3000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (2, 1, 'Huile (L)', 120, 6000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (2, 2, 'Tôle', 80, 25000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (2, 2, 'Clous (kg)', 60, 8000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (2, 3, 'Argent', 6000000, 1);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (3, 1, 'Riz (kg)', 600, 3000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (3, 1, 'Eau (L)', 1000, 1000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (3, 2, 'Bâche', 150, 15000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (3, 2, 'Bois', 100, 10000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (3, 3, 'Argent', 8000000, 1);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (4, 1, 'Riz (kg)', 300, 3000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (4, 1, 'Haricots', 200, 4000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (4, 2, 'Tôle', 40, 25000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (4, 2, 'Clous (kg)', 30, 8000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (4, 3, 'Argent', 4000000, 1);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (5, 1, 'Riz (kg)', 700, 3000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (5, 1, 'Eau (L)', 1200, 1000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (5, 2, 'Bâche', 180, 15000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (5, 2, 'Bois', 150, 10000);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (5, 3, 'Argent', 10000000, 1);
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (1, 2, 'groupe', 3, 6750000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-16 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (1, 5000000, 5000000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-16 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (2, 3000000, 3000000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (3, 4000000, 4000000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (4, 1500000, 1500000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (5, 6000000, 6000000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-16 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (6, 1, 'Riz (kg)', 400);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-16 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (7, 1, 'Eau (L)', 600);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (8, 2, 'Tôle', 50);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (9, 2, 'Bâche', 70);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (10, 1, 'Haricots', 100);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-18 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (11, 1, 'Riz (kg)', 2000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-18 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (12, 2, 'Tôle', 300);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-18 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (13, 1, 'Eau (L)', 5000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('argent', NULL, '2026-02-19 00:00:00', NULL);
INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (14, 20000000, 20000000);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-19 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (15, 2, 'Bâche', 500);
INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES ('nature', NULL, '2026-02-17 00:00:00', NULL);
INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (16, 1, 'Haricots', 88);
INSERT INTO bn_config_achat (frais_pourcentage) VALUES (10.00);

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
