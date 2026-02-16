-- ====================================================================
-- SCRIPT DE MISE À JOUR V2 : FONCTIONNALITÉ D'ACHAT
-- ====================================================================
-- Date : 2026-02-17
-- Description : Ajout de la table bn_achat pour gérer les achats
--               de besoins en nature/matériaux avec les dons en argent
-- ====================================================================

-- Table des achats
CREATE TABLE IF NOT EXISTS bn_achat (
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
-- Un seul enregistrement pour stocker le pourcentage de frais
CREATE TABLE IF NOT EXISTS bn_config_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_pourcentage DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    date_modification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer la configuration par défaut (10% de frais)
INSERT INTO bn_config_achat (frais_pourcentage) VALUES (10.00)
ON DUPLICATE KEY UPDATE frais_pourcentage = frais_pourcentage;

-- ====================================================================
-- FIN DU SCRIPT
-- ====================================================================
