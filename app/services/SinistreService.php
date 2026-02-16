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

    public static function getTotalBesoins()
    {
        $query = Flight::db()->prepare('SELECT SUM(quantite) as total FROM bn_sinistre_besoin');
        $query->execute();
        $result = $query->fetch();
        return $result['total'] ?? 0;
    }

    public static function getSinistresGrouped($details)
    {
        $sinistres = [];
        foreach ($details as $detail) {
            $id = $detail['sinistre_id'];
            if (!isset($sinistres[$id])) {
                $sinistres[$id] = [
                    'id' => $detail['sinistre_id'],
                    'nombre_sinistres' => $detail['nombre_sinistres'],
                    'ville_nom' => $detail['ville_nom'],
                    'region_nom' => $detail['region_nom'],
                    'population' => $detail['ville_population']
                ];
            }
        }
        return array_slice(array_values($sinistres), 0, 10);
    }

    public static function getBesoinsParCategorie($details)
    {
        $categories = [];
        $total = 0;

        foreach ($details as $detail) {
            if (!empty($detail['categorie_besoin_nom'])) {
                $cat = $detail['categorie_besoin_nom'];
                if (!isset($categories[$cat])) {
                    $categories[$cat] = 0;
                }
                $categories[$cat] += $detail['besoin_quantite'];
                $total += $detail['besoin_quantite'];
            }
        }

        $result = [];
        foreach ($categories as $cat => $quantite) {
            $result[] = [
                'categorie' => $cat,
                'quantite_totale' => $quantite,
                'pourcentage' => $total > 0 ? ($quantite / $total) * 100 : 0
            ];
        }

        return $result;
    }

    public static function getTopRegions($details)
    {
        $regions = [];

        foreach ($details as $detail) {
            $region = $detail['region_nom'];
            if (!isset($regions[$region])) {
                $regions[$region] = 0;
            }
            $regions[$region] += $detail['nombre_sinistres'];
        }

        arsort($regions);

        $result = [];
        $count = 0;
        foreach ($regions as $nom => $nombre) {
            if ($count >= 5) {
                break;
            }

            $result[] = [
                'nom' => $nom,
                'nombre_sinistres' => $nombre
            ];
            $count++;
        }

        return $result;
    }
}