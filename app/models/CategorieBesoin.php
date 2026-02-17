<?php
namespace app\models;
use Flight;
class CategorieBesoin
{
    //CRUD basique pour la table categorie_besoin
    // insert
    public static function create($nom)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_categorie_besoin (nom) VALUES (?)');
        $query->execute([$nom]);
    }
    // update
    public static function update($id, $nom)
    {
        $query = Flight::db()->prepare('UPDATE bn_categorie_besoin SET nom = ? WHERE id = ?');
        $query->execute([$nom, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_categorie_besoin');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_categorie_besoin', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->prepare('SELECT * FROM bn_categorie_besoin WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }
}