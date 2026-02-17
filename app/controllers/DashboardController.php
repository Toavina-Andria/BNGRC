<?php
namespace app\controllers;

use flight\Engine;
use app\services\SinistreService;
use app\services\DashboardService;
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

        // Récupérer les villes avec leurs besoins et dons via le service
        $villes = DashboardService::getVillesDashboard();

        // Récupérer les totaux des dons via le service
        $total_dons = DashboardService::getTotalDons();

        $this->app->render('dashboard/dashboard', [
            'basepath' => $this->app->get('base_path'),
            'stats' => $stats,
            'villes' => $villes,
            'total_dons' => $total_dons
        ]);
    }

    /**
     * Page de récapitulation des besoins
     */
    public function recapitulation()
    {
        $data = $this->getRecapitulationData();

        $this->app->render('dashboard/recapitulation', [
            'basepath' => $this->app->get('base_path'),
            'data' => $data
        ]);
    }

    /**
     * API Ajax pour actualiser les données de récapitulation
     */
    public function recapitulationAjax()
    {
        $data = $this->getRecapitulationData();
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Récupérer les données de récapitulation via le service
     */
    private function getRecapitulationData()
    {
        return DashboardService::getRecapitulationData();
    }

}