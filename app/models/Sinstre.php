<?php
namespace app\models;
use Flight;
class Sinstre
{
    //CRUD basique pour la table sinistre
    // insert
    public static function create($nombre_sinistres, $id_ville)
    {
        $query = Flight::db()->prepare('INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES (?, ?)');
        $query->execute([$nombre_sinistres, $id_ville]);
    }
    // update
    public static function update($id, $nombre_sinistres, $id_ville)
    {
        $query = Flight::db()->prepare('UPDATE bn_sinistre SET nombre_sinistres = ?, id_ville = ? WHERE id = ?');
        $query->execute([$nombre_sinistres, $id_ville, $id]);
    }

    // findAll
    public static function findAll()
    {
        $query = Flight::db()->query('SELECT * FROM bn_sinistre');
        return $query->fetchAll();
    }
    // delete
    public static function delete($id)
    {
        Flight::db()->delete('bn_sinistre', ['id' => $id]);
    }
    // findById
    public static function findById($id)
    {
        $query = Flight::db()->query('SELECT * FROM bn_sinistre WHERE id = ?', [$id]);
        return $query->fetch();
    }
}