<?php
namespace app\models;
use Flight;

class Don
{
    /**
     * Créer un don en argent
     */
    public static function createDonArgent($donateur, $montant, $id_ville = null)
    {
        try {
            Flight::db()->beginTransaction();
            
            // Insérer le don
            $query = Flight::db()->prepare('INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES (?, ?, NOW(), ?)');
            $query->execute(['argent', $donateur, $id_ville]);
            $id_don = Flight::db()->lastInsertId();
            
            // Insérer le montant
            $query2 = Flight::db()->prepare('INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES (?, ?, ?)');
            $query2->execute([$id_don, $montant, $montant]);
            
            Flight::db()->commit();
            return $id_don;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }

    /**
     * Créer un don en nature
     */
    public static function createDonNature($donateur, $id_categorie_besoin, $description, $quantite, $id_ville = null)
    {
        try {
            Flight::db()->beginTransaction();
            
            // Insérer le don
            $query = Flight::db()->prepare('INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES (?, ?, NOW(), ?)');
            $query->execute(['nature', $donateur, $id_ville]);
            $id_don = Flight::db()->lastInsertId();
            
            // Insérer les détails du don nature
            $query2 = Flight::db()->prepare('INSERT INTO bn_don_nature (id_don, id_categorie_besoin, description, quantite) VALUES (?, ?, ?, ?)');
            $query2->execute([$id_don, $id_categorie_besoin, $description, $quantite]);
            
            Flight::db()->commit();
            return $id_don;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }

    // findAll avec détails
    public static function findAll()
    {
        $query = Flight::db()->query('
            SELECT d.*, 
                   da.montant, da.montant_restant,
                   dn.id_categorie_besoin, dn.description, dn.quantite,
                   cb.nom as categorie_nom,
                   v.nom as ville_nom
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
            LEFT JOIN bn_don_nature dn ON d.id = dn.id_don
            LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
            LEFT JOIN bn_ville v ON d.id_ville = v.id
            ORDER BY d.date_don ASC
        ');
        return $query->fetchAll();
    }

    // findById
    public static function findById($id)
    {
        $query = Flight::db()->prepare('
            SELECT d.*, 
                   da.montant, da.montant_restant,
                   dn.id_categorie_besoin, dn.description, dn.quantite,
                   cb.nom as categorie_nom
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
            LEFT JOIN bn_don_nature dn ON d.id = dn.id_don
            LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
            WHERE d.id = ?
        ');
        $query->execute([$id]);
        return $query->fetch();
    }

    // Récupérer les dons non attribués (disponibles pour dispatch)
    public static function getDonsDisponibles()
    {
        $query = Flight::db()->query('
            SELECT d.*, 
                   da.montant, da.montant_restant,
                   dn.id_categorie_besoin, dn.description, dn.quantite,
                   cb.nom as categorie_nom
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don AND da.montant_restant > 0
            LEFT JOIN bn_don_nature dn ON d.id = dn.id_don AND dn.quantite > 0
            LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
            WHERE (d.type = "argent" AND da.montant_restant > 0) 
               OR (d.type = "nature" AND dn.quantite > 0)
            ORDER BY d.date_don ASC
        ');
        return $query->fetchAll();
    }

    // delete
    public static function delete($id)
    {
        try {
            Flight::db()->beginTransaction();
            
            // Supprimer les détails (cascade devrait le faire mais on s'assure)
            Flight::db()->prepare('DELETE FROM bn_don_argent WHERE id_don = ?')->execute([$id]);
            Flight::db()->prepare('DELETE FROM bn_don_nature WHERE id_don = ?')->execute([$id]);
            Flight::db()->prepare('DELETE FROM bn_don WHERE id = ?')->execute([$id]);
            
            Flight::db()->commit();
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }
}
