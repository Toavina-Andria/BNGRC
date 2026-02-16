-- Migration: Ajout du prix unitaire aux besoins
-- Date: 17 février 2026

USE bngrc;

-- Ajouter la colonne prix_unitaire si elle n'existe pas déjà
ALTER TABLE bn_sinistre_besoin 
ADD COLUMN IF NOT EXISTS prix_unitaire DECIMAL(10, 2) NOT NULL DEFAULT 0;

