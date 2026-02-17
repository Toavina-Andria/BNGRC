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
                
            } elseif ($type == 'nature') {
                $id_categorie = $this->app->request()->data->id_categorie_besoin;
                $description = Validator::sanitizeString($this->app->request()->data->description);
                $quantite = $this->app->request()->data->quantite;

                if (empty($id_categorie) || !Validator::validatePositiveInteger($quantite)) {
                    $this->app->halt(400, "Catégorie et quantité (entier positif) obligatoires.");
                }

                // Vérifier que la catégorie existe
                $categorie = CategorieBesoin::findById($id_categorie);

                if (!$categorie) {
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

    // Dispatcher automatiquement les dons aux besoins avec la méthode choisie
    public function dispatchDons()
    {
        try {
            $methode = $_POST['methode'] ?? $_GET['methode'] ?? 'quantite';
            
            // Valider la méthode
            $methodes_valides = ['quantite', 'proportionnalite', 'ordre'];
            if (!in_array($methode, $methodes_valides)) {
                $methode = 'quantite';
            }

            $result = DonService::dispatchDons($methode);
            
            $this->app->render('don/dispatch', [
                'basepath' => $this->app->get('base_path'),
                'result' => $result,
                'simulation' => false
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    // Simuler le dispatch sans l'appliquer avec la méthode choisie
    public function simulateDispatch()
    {
        try {
            $methode = $_POST['methode'] ?? $_GET['methode'] ?? 'quantite';
            
            // Valider la méthode
            $methodes_valides = ['quantite', 'proportionnalite', 'ordre'];
            if (!in_array($methode, $methodes_valides)) {
                $methode = 'quantite';
            }

            $result = DonService::simulateDispatch($methode);
            
            $this->app->render('don/dispatch', [
                'basepath' => $this->app->get('base_path'),
                'result' => $result,
                'simulation' => true,
                'methode' => $methode
            ]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }
}
