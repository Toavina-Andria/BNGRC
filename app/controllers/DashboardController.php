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
            'total_besoins' => SinistreService::getTotalBesoins()
        ];

        // Récupérer les villes avec leurs besoins et dons (vue principale)
        $query = Flight::db()->query('SELECT * FROM v_dashboard_ville ORDER BY montant_total_besoins DESC');
        $villes = $query->fetchAll();

        // Récupérer les totaux des dons
        $query_total_dons = Flight::db()->query('
            SELECT 
                COUNT(DISTINCT d.id) as nb_total_dons,
                SUM(CASE WHEN d.type = "argent" THEN da.montant_restant ELSE 0 END) as total_argent_disponible
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
        ');
        $total_dons = $query_total_dons->fetch();

        $this->app->render('dashboard/dashboard', [
            'basepath' => $this->app->get('base_path'),
            'stats' => $stats,
            'villes' => $villes,
            'total_dons' => $total_dons
        ]);
    }

}