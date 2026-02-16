<?php
namespace app\models;
use Flight;
class SinistreBesoin
{
    //CRUD basique pour la table sinistre_besoin
    // insert
    public static function create($id_sinistre, $id_categorie_besoin, $description, $quantite, $prix_unitaire)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire) VALUES (?, ?, ?, ?, ?)');
        $query->execute([$id_sinistre, $id_categorie_besoin, $description, $quantite, $prix_unitaire]);
        return Flight::db()->lastInsertId();
    }
    
    // update
    public static function update($id, $id_sinistre, $id_categorie_besoin, $description, $quantite, $prix_unitaire)
    {
        $query = Flight::db()->prepare('UPDATE bn_sinistre_besoin SET id_sinistre = ?, id_categorie_besoin = ?, description = ?, quantite = ?, prix_unitaire = ? WHERE id = ?');
        $query->execute([$id_sinistre, $id_categorie_besoin, $description, $quantite, $prix_unitaire, $id]);
    }

    // findAll avec détails
    public static function findAll()
    {
        $query = Flight::db()->query('
            SELECT sb.*, cb.nom as categorie_nom, 
                   s.id_ville, v.nom as ville_nom, s.nombre_sinistres
            FROM bn_sinistre_besoin sb
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            JOIN bn_sinistre s ON sb.id_sinistre = s.id
            JOIN bn_ville v ON s.id_ville = v.id
        ');
        return $query->fetchAll();
    }
    
    // Récupérer les besoins d'un sinistre
    public static function findBySinistre($id_sinistre)
    {
        $query = Flight::db()->prepare('
            SELECT sb.*, cb.nom as categorie_nom
            FROM bn_sinistre_besoin sb
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            WHERE sb.id_sinistre = ?
        ');
        $query->execute([$id_sinistre]);
        return $query->fetchAll();
    }
    
    // Récupérer les besoins d'une ville
    public static function findByVille($id_ville)
    {
        $query = Flight::db()->prepare('
            SELECT sb.*, cb.nom as categorie_nom, s.nombre_sinistres
            FROM bn_sinistre_besoin sb
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            JOIN bn_sinistre s ON sb.id_sinistre = s.id
            WHERE s.id_ville = ? AND sb.quantite > 0
            ORDER BY cb.nom
        ');
        $query->execute([$id_ville]);
        return $query->fetchAll();
    }
    
    // delete
    public static function delete($id)
    {
        Flight::db()->prepare('DELETE FROM bn_sinistre_besoin WHERE id = ?')->execute([$id]);
    }
    
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->prepare('
            SELECT sb.*, cb.nom as categorie_nom
            FROM bn_sinistre_besoin sb
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            WHERE sb.id = ?
        ');
        $query->execute([$id]);
        return $query->fetch();
    }
}