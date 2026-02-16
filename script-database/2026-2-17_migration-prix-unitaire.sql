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

