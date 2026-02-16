<?php
namespace app\models;
use Flight;
class Region
{
    //CRUD basique pour la table region
    // insert
    public static function create($nom)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_region (nom) VALUES (?)');
        $query->execute([$nom]);
    }
    // update
    public static function update($id, $nom)
    {
        $query = Flight::db()->prepare('UPDATE bn_region SET nom = ? WHERE id = ?');
        $query->execute([$nom, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_region');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_region', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->query('SELECT * FROM bn_region WHERE id = ?', [$id]);
        return $query->fetch();
    }
}