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

        // Get all sinistre details from view
        $allDetails = SinistreService::getSinistreDetails();

        // Get recent sinistres (grouped by sinistre_id)
        $sinistres = SinistreService::getSinistresGrouped($allDetails);

        // Get besoins by category
        $besoins_categories = SinistreService::getBesoinsParCategorie($allDetails);

        // Get top regions affected
        $top_regions = SinistreService::getTopRegions($allDetails);

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

}