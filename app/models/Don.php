<?php
namespace app\models;
use Flight;

class Don
{
    // insert
    public static function create($idCategorieBesoin, $donateur, $description, $quantite)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_don (id_categorie_besoin, donateur, description, quantite, date_don) VALUES (?, ?, ?, ?, NOW())');
        $query->execute([$idCategorieBesoin, $donateur, $description, $quantite]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_don ORDER BY date_don ASC');
        return $query->fetchAll();
    }

    // findById
    public static function findById($id)
    {
        $query = Flight::db()->prepare('SELECT * FROM bn_don WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    // delete
    public static function delete($id)
    {
        Flight::db()->prepare('DELETE FROM bn_don WHERE id = ?')->execute([$id]);
    }
}
