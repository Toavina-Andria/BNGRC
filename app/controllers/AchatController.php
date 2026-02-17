<?php
namespace app\controllers;
use app\models\Achat;
use app\models\SinistreBesoin;
use app\models\Ville;
use app\services\AchatService;
use app\utils\Validator;
use Flight;
use Throwable;

class AchatController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Afficher la liste des besoins restants pour faire des achats
     */
    public function showBesoinsRestants()
    {
        try {
            // Récupérer les filtres
            $id_ville = $_GET['id_ville'] ?? null;
            
            // Récupérer les besoins restants via le service
            $besoins = AchatService::getBesoinsRestants($id_ville ? intval($id_ville) : null);

            // Récupérer toutes les villes pour le filtre
            $villes = Ville::findAll();

            // Récupérer le pourcentage de frais
            $frais_pourcentage = Achat::getFraisPourcentage();

            $this->app->render('achat/besoins_restants', [
                'basepath' => $this->app->get('base_path'),
                'besoins' => $besoins,
                'villes' => $villes,
                'id_ville_filtre' => $id_ville,
                'frais_pourcentage' => $frais_pourcentage
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire d'achat pour un besoin spécifique
     */
    public function showAchatForm()
    {
        try {
            $id_besoin = $_GET['id_besoin'] ?? null;

            if (!$id_besoin || !Validator::validateId($id_besoin)) {
                $this->app->halt(400, "ID besoin invalide.");
            }

            // Récupérer le besoin avec ses détails via le service
            $besoin = AchatService::getBesoinWithDetails($id_besoin);

            if (!$besoin) {
                $this->app->halt(404, "Besoin non trouvé ou déjà satisfait.");
            }

            // Vérifier si le besoin existe dans les dons nature disponibles
            $existe_dans_dons = Achat::besoinExisteDansDonsNature(
                $besoin['id_categorie'], 
                $besoin['quantite']
            );

            // Récupérer les dons en argent disponibles via le service
            $dons_argent = AchatService::getDonsArgentDisponibles();

            // Récupérer le pourcentage de frais
            $frais_pourcentage = Achat::getFraisPourcentage();

            $this->app->render('achat/form', [
                'basepath' => $this->app->get('base_path'),
                'besoin' => $besoin,
                'existe_dans_dons' => $existe_dans_dons,
                'dons_argent' => $dons_argent,
                'frais_pourcentage' => $frais_pourcentage
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    /**
     * Traiter l'insertion d'un achat
     */
    public function insertAchat()
    {
        try {
            $id_besoin = $this->app->request()->data->id_besoin;
            $id_don_argent = $this->app->request()->data->id_don_argent;
            $quantite = $this->app->request()->data->quantite;

            // Validation
            if (!Validator::validateId($id_besoin) || 
                !Validator::validateId($id_don_argent) || 
                !Validator::validatePositiveInteger($quantite)) {
                $this->app->halt(400, "Données invalides.");
            }

            $quantite = intval($quantite);

            // Traiter l'achat via le service (qui gère la transaction complète)
            AchatService::processAchat($id_besoin, $id_don_argent, $quantite);

            // Rediriger vers la liste des achats
            $this->app->redirect('/achats/liste');

        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    /**
     * Lister tous les achats
     */
    public function listeAchats()
    {
        try {
            // Récupérer le filtre ville
            $id_ville = $_GET['id_ville'] ?? null;

            // Récupérer les achats
            $achats = Achat::findAll($id_ville ? intval($id_ville) : null);

            // Récupérer toutes les villes pour le filtre
            $villes = Ville::findAll();

            $this->app->render('achat/liste', [
                'basepath' => $this->app->get('base_path'),
                'achats' => $achats,
                'villes' => $villes,
                'id_ville_filtre' => $id_ville
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }
}
