<?php
namespace app\services;
use app\models\Sinistre;
use Flight;
class SinistreService
{
    protected Sinistre $model;

    public function __construct(Sinistre $model)
    {
        $this->model = $model;
    }

    public static function getAllSinistres()
    {
        return Sinistre::findAll();
    }

    public function getSinistreById($id)
    {
        return Sinistre::findById($id);
    }

    public function getSinistresByRegion($region)
    {
        $query = Flight::db()->prepare('SELECT * FROM sinistre_details WHERE region_nom = ?');
        $query->execute([$region]);
        return $query->fetchAll();
    }

    public static function getTotalSinistres()
    {
        $query = Flight::db()->query('SELECT SUM(nombre_sinistres) as total FROM bn_sinistre');
        $result = $query->fetch();
        return $result['total'] ?? 0;
    }

    public static function getCountVilleAffecter()
    {
        $query = Flight::db()->query('SELECT COUNT(DISTINCT id_ville) as count FROM bn_sinistre');
        $result = $query->fetch();
        return $result['count'] ?? 0;
    }

    public static function getCountRegion()
    {
        $query = Flight::db()->query('
            SELECT COUNT(DISTINCT r.id) as count
            FROM bn_region r
            JOIN bn_ville v ON r.id = v.id_region
            JOIN bn_sinistre s ON v.id = s.id_ville
        ');
        $result = $query->fetch();
        return $result['count'] ?? 0;
    }

    public static function getSinistreDetails()
    {
        $query = Flight::db()->query('SELECT * FROM sinistre_details');
        return $query->fetchAll();
    }
}