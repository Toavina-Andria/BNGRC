<?php
namespace app\controllers;

use flight\Engine;
use app\services\SinistreService;
use Flight;

class DashboardController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }


    public function index()
    {
        // Get statistics using SinistreService
        $stats = [
            'total_sinistres' => SinistreService::getTotalSinistres(),
            'villes_affectees' => SinistreService::getCountVilleAffecter(),
            'total_regions' => SinistreService::getCountRegion(),
            'total_besoins' => $this->getTotalBesoins()
        ];

        // Get all sinistre details from view
        $allDetails = SinistreService::getSinistreDetails();

        // Get recent sinistres (grouped by sinistre_id)
        $sinistres = $this->getSinistresGrouped($allDetails);

        // Get besoins by category
        $besoins_categories = $this->getBesoinsParCategorie($allDetails);

        // Get top regions affected
        $top_regions = $this->getTopRegions($allDetails);

        // Get detailed besoins list
        $besoins_details = $allDetails;

        $this->app->render('dashboard/dashboard', [
            'basepath' => $this->app->get('basepath'),
            'stats' => $stats,
            'sinistres' => $sinistres,
            'besoins_categories' => $besoins_categories,
            'top_regions' => $top_regions,
            'besoins_details' => $besoins_details
        ]);
    }

    private function getTotalBesoins()
    {
        $query = Flight::db()->query('SELECT SUM(quantite) as total FROM bn_sinistre_besoin');
        $result = $query->fetch();
        return $result['total'] ?? 0;
    }

    private function getSinistresGrouped($details)
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
        return array_slice(array_values($sinistres), 0, 10); // Limit to 10 recent
    }

    private function getBesoinsParCategorie($details)
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

    private function getTopRegions($details)
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
            } // Top 5 regions

            $result[] = [
                'nom' => $nom,
                'nombre_sinistres' => $nombre
            ];
            $count++;
        }

        return $result;
    }
}