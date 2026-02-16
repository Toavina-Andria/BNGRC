-- Migration: Ajout du prix unitaire aux besoins
-- Date: 17 février 2026

DROP DATABASE IF EXISTS bngrc;
CREATE DATABASE bngrc;
DROP TABLE IF EXISTS bn_don_nature;
DROP TABLE IF EXISTS bn_don_argent;
DROP TABLE IF EXISTS bn_don;
DROP TABLE IF EXISTS bn_sinistre_besoin;
DROP TABLE IF EXISTS bn_categorie_besoin;
DROP TABLE IF EXISTS bn_sinistre;
DROP TABLE IF EXISTS bn_ville;
DROP TABLE IF EXISTS bn_region;


USE bngrc;

-- Ajouter la colonne prix_unitaire si elle n'existe pas déjà
ALTER TABLE bn_sinistre_besoin 
ADD COLUMN IF NOT EXISTS prix_unitaire DECIMAL(10, 2) NOT NULL DEFAULT 0;

-- Mettre à jour les prix unitaires par défaut selon la catégorie
-- (Vous pouvez ajuster ces valeurs selon vos besoins)
UPDATE bn_sinistre_besoin sb
JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
SET sb.prix_unitaire = CASE cb.nom
    WHEN 'Riz' THEN 4000
    WHEN 'Huile' THEN 8000
    WHEN 'Sucre' THEN 5000
    WHEN 'Tôle' THEN 25000
    WHEN 'Clous' THEN 2000
    WHEN 'Vêtements' THEN 10000
    WHEN 'Couvertures' THEN 15000
    WHEN 'Médicaments' THEN 20000
    ELSE 5000
END
WHERE sb.prix_unitaire = 0;

SELECT 'Migration terminée: colonne prix_unitaire ajoutée' AS message;
SELECT COUNT(*) AS nb_besoins_mis_a_jour FROM bn_sinistre_besoin WHERE prix_unitaire > 0;
