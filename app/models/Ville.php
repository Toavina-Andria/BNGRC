<?php
namespace app\models;
use Flight;
class Ville
{
    //CRUD basique pour la table ville
    // insert
    public static function create($nom, $population, $id_region)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_ville (nom, population, id_region) VALUES (?, ?, ?)');
        $query->execute([$nom, $population, $id_region]);
    }
    // update
    public static function update($id, $nom, $population, $id_region)
    {
        $query = Flight::db()->prepare('UPDATE bn_ville SET nom = ?, population = ?, id_region = ? WHERE id = ?');
        $query->execute([$nom, $population, $id_region, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_ville');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_ville', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->query('SELECT * FROM bn_ville WHERE id = ?', [$id]);
        return $query->fetch();
    }
}