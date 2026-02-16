<?php
namespace app\controllers;

use flight\Engine;
use app\models\Ville;
use app\models\SinistreBesoin;
use Flight;

class VilleController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Afficher les détails d'une ville avec ses besoins et dons
     */
    public function showVilleBesoins()
    {
        $id_ville = Flight::request()->query->id;
        
        if (!$id_ville) {
            Flight::redirect('/');
            return;
        }

        // Récupérer les informations de la ville
        $ville = Ville::findById($id_ville);
        
        if (!$ville) {
            Flight::redirect('/');
            return;
        }

        // Récupérer les sinistres de la ville
        $query = Flight::db()->prepare('
            SELECT s.*, COUNT(sb.id) as nb_besoins
            FROM bn_sinistre s
            LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
            WHERE s.id_ville = ?
            GROUP BY s.id
        ');
        $query->execute([$id_ville]);
        $sinistres = $query->fetchAll();

        // Récupérer les besoins de la ville
        $besoins = SinistreBesoin::findByVille($id_ville);

        // Récupérer les dons attribués à cette ville
        $query_dons = Flight::db()->prepare('
            SELECT d.*, 
                   da.montant, da.montant_restant,
                   dn.id_categorie_besoin, dn.description, dn.quantite,
                   cb.nom as categorie_nom
            FROM bn_don d
            LEFT JOIN bn_don_argent da ON d.id = da.id_don
            LEFT JOIN bn_don_nature dn ON d.id = dn.id_don
            LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
            WHERE d.id_ville = ?
            ORDER BY d.date_don DESC
        ');
        $query_dons->execute([$id_ville]);
        $dons = $query_dons->fetchAll();

        // Calculer les totaux
        $total_besoins_quantite = 0;
        $total_besoins_montant = 0;
        $besoins_par_categorie = [];

        foreach ($besoins as $besoin) {
            $total_besoins_quantite += $besoin['quantite'];
            $total_besoins_montant += $besoin['quantite'] * $besoin['prix_unitaire'];
            
            $cat = $besoin['categorie_nom'];
            if (!isset($besoins_par_categorie[$cat])) {
                $besoins_par_categorie[$cat] = [
                    'quantite' => 0,
                    'montant' => 0
                ];
            }
            $besoins_par_categorie[$cat]['quantite'] += $besoin['quantite'];
            $besoins_par_categorie[$cat]['montant'] += $besoin['quantite'] * $besoin['prix_unitaire'];
        }

        // Calculer les totaux des dons
        $total_dons_argent = 0;
        $total_dons_nature = 0;

        foreach ($dons as $don) {
            if ($don['type'] == 'argent') {
                $total_dons_argent += $don['montant_restant'];
            } else {
                $total_dons_nature++;
            }
        }

        $this->app->render('ville/besoins', [
            'basepath' => $this->app->get('base_path'),
            'ville' => $ville,
            'sinistres' => $sinistres,
            'besoins' => $besoins,
            'dons' => $dons,
            'total_besoins_quantite' => $total_besoins_quantite,
            'total_besoins_montant' => $total_besoins_montant,
            'besoins_par_categorie' => $besoins_par_categorie,
            'total_dons_argent' => $total_dons_argent,
            'total_dons_nature' => $total_dons_nature
        ]);
    }
}
