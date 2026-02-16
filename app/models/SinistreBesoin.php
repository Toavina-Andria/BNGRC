<?php
namespace app\models;
use Flight;
class SinistreBesoin
{
    //CRUD basique pour la table sinistre_besoin
    // insert
    public static function create($id_sinistre, $id_categorie_besoin, $description, $quantite)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite) VALUES (?, ?, ?, ?)');
        $query->execute([$id_sinistre, $id_categorie_besoin, $description, $quantite]);
    }
    // update
    public static function update($id, $id_sinistre, $id_categorie_besoin, $description, $quantite)
    {
        $query = Flight::db()->prepare('UPDATE bn_sinistre_besoin SET id_sinistre = ?, id_categorie_besoin = ?, description = ?, quantite = ? WHERE id = ?');
        $query->execute([$id_sinistre, $id_categorie_besoin, $description, $quantite, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_sinistre_besoin');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_sinistre_besoin', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->query('SELECT * FROM bn_sinistre_besoin WHERE id = ?', [$id]);
        return $query->fetch();
    }
}