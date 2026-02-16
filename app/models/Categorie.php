<?php
namespace app\models;
use Flight;
class Categorie
{
    //CRUD basique pour la table categorie
    // insert
    public static function create($nom)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_categorie (nom) VALUES (?)');
        $query->execute([$nom]);
    }
    // update
    public static function update($id, $nom)
    {
        $query = Flight::db()->prepare('UPDATE bn_categorie SET nom = ? WHERE id = ?');
        $query->execute([$nom, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_categorie');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_categorie', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->query('SELECT * FROM bn_categorie WHERE id = ?', [$id]);
        return $query->fetch();
    }
}