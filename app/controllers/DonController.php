<?php
namespace app\controllers;
use app\models\Don;
use app\models\Ville;
use app\models\CategorieBesoin;
use app\services\DonService;
use app\utils\Validator;
use flight\Engine;
use Flight;
use Throwable;


class DonController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }

    // Affiche le formulaire d'ajout de don
    public function showDonForm()
    {
        // Récupérer les catégories et villes pour le formulaire
        $categories = CategorieBesoin::findAll();
        $villes = Ville::findAll();
        
        $this->app->render('don/form', [
            'basepath' => $this->app->get('base_path'),
            'categories' => $categories,
            'villes' => $villes
        ]);
    }

    // Insere un don en base
    public function insertDon()
    {
        try {
            $type = $this->app->request()->data->type;
            $donateur = Validator::sanitizeString($this->app->request()->data->donateur);
            $id_ville = $this->app->request()->data->id_ville ?: null;

            if (empty($type)) {
                $this->app->halt(400, "Le type de don est obligatoire.");
            }

            if ($type == 'argent') {
                $montant = $this->app->request()->data->montant;
                
                if (!Validator::validatePositiveAmount($montant)) {
                    $this->app->halt(400, "Le montant doit être un nombre positif supérieur à 0.");
                }

                Don::createDonArgent($donateur, floatval($montant), $id_ville);
                
            } else if ($type == 'nature') {
                $id_categorie = $this->app->request()->data->id_categorie_besoin;
                $description = Validator::sanitizeString($this->app->request()->data->description);
                $quantite = $this->app->request()->data->quantite;

                if (empty($id_categorie) || !Validator::validatePositiveInteger($quantite)) {
                    $this->app->halt(400, "Catégorie et quantité (entier positif) obligatoires.");
                }

                // Vérifier que la catégorie existe
                $queryCategorie = Flight::db()->prepare('SELECT id FROM bn_categorie_besoin WHERE id = ?');
                $queryCategorie->execute([$id_categorie]);
                $categorieExists = $queryCategorie->fetch();

                if (!$categorieExists) {
                    $this->app->halt(400, "Catégorie invalide ou inexistante.");
                }

                Don::createDonNature($donateur, intval($id_categorie), $description, intval($quantite), $id_ville);
                
            } else {
                $this->app->halt(400, "Type de don invalide.");
            }

            $this->app->redirect('/dons/liste');
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    // Liste tous les dons
    public function listeDons()
    {
        $dons = DonService::getDonsWithDetails();
        $this->app->render('don/liste', [
            'basepath' => $this->app->get('base_path'),
            'dons' => $dons
        ]);
    }

    // Dispatcher automatiquement les dons aux besoins
    public function dispatchDons()
    {
        try {
            $result = DonService::dispatchDons();
            
            $this->app->render('don/dispatch', [
                'basepath' => $this->app->get('base_path'),
                'result' => $result,
                'simulation' => false
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    // Simuler le dispatch sans l'appliquer
    public function simulateDispatch()
    {
        try {
            $result = DonService::simulateDispatch();
            
            $this->app->render('don/dispatch', [
                'basepath' => $this->app->get('base_path'),
                'result' => $result,
                'simulation' => true
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }
}
