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
     * Récupérer les données de récapitulation
     */
    private function getRecapitulationData()
    {
        // Calculer les montants totaux des besoins
        $query_besoins_totaux = Flight::db()->query('
            SELECT 
                SUM(quantite * prix_unitaire) as montant_total,
                SUM(quantite) as quantite_totale
            FROM bn_sinistre_besoin
        ');
        $besoins_totaux = $query_besoins_totaux->fetch();

        // Calculer les montants des besoins restants (non satisfaits)
        $query_besoins_restants = Flight::db()->query('
            SELECT 
                SUM(quantite * prix_unitaire) as montant_restant,
                SUM(quantite) as quantite_restante
            FROM bn_sinistre_besoin
            WHERE quantite > 0
        ');
        $besoins_restants = $query_besoins_restants->fetch();

        // Calculer les besoins satisfaits
        $montant_satisfait = floatval($besoins_totaux['montant_total']) - floatval($besoins_restants['montant_restant']);
        $quantite_satisfaite = intval($besoins_totaux['quantite_totale']) - intval($besoins_restants['quantite_restante']);

        // Calculer le taux de couverture
        $taux_couverture = 0;
        if (floatval($besoins_totaux['montant_total']) > 0) {
            $taux_couverture = ($montant_satisfait / floatval($besoins_totaux['montant_total'])) * 100;
        }

        // Récupérer les détails par catégorie
        $query_par_categorie = Flight::db()->query('
            SELECT 
                cb.nom as categorie,
                SUM(sb.quantite * sb.prix_unitaire) as montant_total_categorie,
                SUM(CASE WHEN sb.quantite = 0 THEN sb.prix_unitaire ELSE 0 END) as montant_satisfait_categorie,
                SUM(CASE WHEN sb.quantite > 0 THEN sb.quantite * sb.prix_unitaire ELSE 0 END) as montant_restant_categorie
            FROM bn_sinistre_besoin sb
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            GROUP BY cb.id, cb.nom
            ORDER BY montant_total_categorie DESC
        ');
        $par_categorie = $query_par_categorie->fetchAll();

        // Récupérer les statistiques des dons
        $query_dons = Flight::db()->query('
            SELECT 
                COUNT(DISTINCT CASE WHEN d.type = "argent" THEN d.id END) as nb_dons_argent,
                COUNT(DISTINCT CASE WHEN d.type = "nature" THEN d.id END) as nb_dons_nature,
                SUM(CASE WHEN d.type = "argent" THEN da.montant ELSE 0 END) as montant_total_argent,
                SUM(CASE WHEN d.type = "argent" THEN da.montant_restant ELSE 0 END) as montant_restant_argent
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
        ');
        $dons = $query_dons->fetch();

        return [
            'besoins' => [
                'montant_total' => floatval($besoins_totaux['montant_total']),
                'montant_satisfait' => $montant_satisfait,
                'montant_restant' => floatval($besoins_restants['montant_restant']),
                'quantite_totale' => intval($besoins_totaux['quantite_totale']),
                'quantite_satisfaite' => $quantite_satisfaite,
                'quantite_restante' => intval($besoins_restants['quantite_restante']),
                'taux_couverture' => round($taux_couverture, 2)
            ],
            'par_categorie' => $par_categorie,
            'dons' => $dons,
            'derniere_mise_a_jour' => date('Y-m-d H:i:s')
        ];
    }

}