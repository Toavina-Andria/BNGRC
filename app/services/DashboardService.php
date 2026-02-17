<?php
namespace app\services;
use Flight;

class DashboardService
{
    /**
     * Récupérer les villes avec leurs besoins et dons depuis la vue dashboard
     * @return array Liste des villes avec statistiques
     */
    public static function getVillesDashboard()
    {
        $query = Flight::db()->query('SELECT * FROM v_dashboard_ville ORDER BY montant_total_besoins DESC');
        return $query->fetchAll();
    }

    /**
     * Récupérer les totaux des dons
     * @return array Statistiques des dons (nombre total et argent disponible)
     */
    public static function getTotalDons()
    {
        $query = Flight::db()->query('
            SELECT 
                COUNT(DISTINCT d.id) as nb_total_dons,
                SUM(CASE WHEN d.type = "argent" THEN da.montant_restant ELSE 0 END) as total_argent_disponible
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
        ');
        return $query->fetch();
    }

    /**
     * Calculer les montants totaux des besoins
     * @return array Montant total et quantité totale
     */
    public static function getBesoinsTotaux() 
    {
        $query = Flight::db()->query('
            SELECT 
                SUM(quantite * prix_unitaire) as montant_total,
                SUM(quantite) as quantite_totale
            FROM bn_sinistre_besoin
        ');
        return $query->fetch();
    }

    /**
     * Calculer les montants des besoins restants (non satisfaits)
     * @return array Montant restant et quantité restante
     */
    public static function getBesoinsRestants()
    {
        $query = Flight::db()->query('
            SELECT
                SUM(quantite * prix_unitaire) as montant_restant,
                SUM(quantite) as quantite_restante
            FROM bn_sinistre_besoin
            WHERE quantite > 0
        ');
        return $query->fetch();
    }

    /**
     * Récupérer les détails des besoins par catégorie
     * @return array Détails par catégorie
     */
    public static function getBesoinsParCategorie()
    {
        $query = Flight::db()->query('
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
        return $query->fetchAll();
    }

    /**
     * Récupérer les statistiques des dons
     * @return array Statistiques complètes des dons
     */
    public static function getDonsStatistics()
    {
        $query = Flight::db()->query('
            SELECT
                COUNT(DISTINCT CASE WHEN d.type = "argent" THEN d.id END) as nb_dons_argent,
                COUNT(DISTINCT CASE WHEN d.type = "nature" THEN d.id END) as nb_dons_nature,
                SUM(CASE WHEN d.type = "argent" THEN da.montant ELSE 0 END) as montant_total_argent,
                SUM(CASE WHEN d.type = "argent" THEN da.montant_restant ELSE 0 END) as montant_restant_argent
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
        ');
        return $query->fetch();
    }

    /**
     * Récupérer toutes les données de récapitulation
     * @return array Données complètes pour la page de récapitulation
     */
    public static function getRecapitulationData()
    {
        // Calculer les montants totaux des besoins
        $besoins_totaux = self::getBesoinsTotaux();

        // Calculer les montants des besoins restants (non satisfaits)
        $besoins_restants = self::getBesoinsRestants();

        // Calculer les besoins satisfaits
        $montant_satisfait = floatval($besoins_totaux['montant_total']) - floatval($besoins_restants['montant_restant']);
        $quantite_satisfaite = intval($besoins_totaux['quantite_totale']) - intval($besoins_restants['quantite_restante']);

        // Calculer le taux de couverture
        $taux_couverture = 0;
        if (floatval($besoins_totaux['montant_total']) > 0) {
            $taux_couverture = ($montant_satisfait / floatval($besoins_totaux['montant_total'])) * 100;
        }

        // Récupérer les détails par catégorie
        $par_categorie = self::getBesoinsParCategorie();

        // Récupérer les statistiques des dons
        $dons = self::getDonsStatistics();

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
