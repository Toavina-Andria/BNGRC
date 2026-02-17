<?php
namespace app\models;
use Flight;
class Data
{

 public static function restoreDefaultData()
    {
        $db = Flight::db();
        // Insérer les données de villes
        $db->exec("
            INSERT INTO bn_ville (nom, population, id_region) VALUES
            ('Toamasina', 326286, 3),
            ('Mananjary', 30000, 1),
            ('Farafangana', 24000, 2),
            ('Nosy Be', 73000, 4),
            ('Morondava', 70000, 5)
        ");

        // Insérer les données de sinistres
        $db->exec("
            INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES
            (1200, 1),
            (600, 2),
            (800, 3),
            (400, 4),
            (1000, 5)
        ");

        // Insérer les données de besoins des sinistres
        $db->exec("
            INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES
            (1, 1, 'Riz (kg)', 800, 3000),
            (1, 1, 'Eau (L)', 1500, 1000),
            (1, 2, 'Tôle', 120, 25000),
            (1, 2, 'Bâche', 200, 15000),
            (1, 3, 'Argent', 12000000, 1),
            (2, 1, 'Riz (kg)', 500, 3000),
            (2, 1, 'Huile (L)', 120, 6000),
            (2, 2, 'Tôle', 80, 25000),
            (2, 2, 'Clous (kg)', 60, 8000),
            (2, 3, 'Argent', 6000000, 1),
            (3, 1, 'Riz (kg)', 600, 3000),
            (3, 1, 'Eau (L)', 1000, 1000),
            (3, 2, 'Bâche', 150, 15000),
            (3, 2, 'Bois', 100, 10000),
            (3, 3, 'Argent', 8000000, 1),
            (4, 1, 'Riz (kg)', 300, 3000),
            (4, 1, 'Haricots', 200, 4000),
            (4, 2, 'Tôle', 40, 25000),
            (4, 2, 'Clous (kg)', 30, 8000),
            (4, 3, 'Argent', 4000000, 1),
            (5, 1, 'Riz (kg)', 700, 3000),
            (5, 1, 'Eau (L)', 1200, 1000),
            (5, 2, 'Bâche', 180, 15000),
            (5, 2, 'Bois', 150, 10000),
            (5, 3, 'Argent', 10000000, 1),
            (1, 2, 'groupe', 3, 6750000)
        ");

        // Insérer les dons
        $db->exec("
            INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES
            ('argent', 'Anonyme', '2026-02-16 00:00:00', NULL),
            ('argent', 'Anonyme', '2026-02-16 00:00:00', NULL),
            ('argent', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('argent', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('argent', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-16 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-16 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-17 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-18 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-18 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-18 00:00:00', NULL),
            ('argent', 'Anonyme', '2026-02-19 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-19 00:00:00', NULL),
            ('nature', 'Anonyme', '2026-02-17 00:00:00', NULL)
        ");

        // Insérer les dons en argent
        $db->exec("
            INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES
            (1, 5000000, 5000000),
            (2, 3000000, 3000000),
            (3, 4000000, 4000000),
            (4, 2000000, 2000000),
            (5, 6000000, 6000000),
            (14, 20000000, 20000000)
        ");

        // Insérer les dons en nature
        $db->exec("
            INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES
            (6, 1, 'Riz (kg)', 900),
            (7, 1, 'Eau (L)', 3700),
            (8, 2, 'Tôle', 240),
            (9, 2, 'Bâche', 530),
            (10, 1, 'Haricots', 200),
            (11, 1, 'Huile (L)', 120),
            (12, 2, 'Clous (kg)', 90),
            (13, 2, 'Bois', 250),
            (15, 1, 'Riz (kg)', 2000),
            (16, 1, 'Eau (L)', 0)
        ");
    }
}