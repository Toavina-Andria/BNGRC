<?php
namespace app\controllers;
use app\models\Don;
use app\services\DonService;
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
        $this->app->render('don/form');
    }

    // Insere un don en base
    public function insertDon()
    {
        try {
            $idCategorie = $this->app->request()->data->id_categorie_besoin;
            $donateur = $this->app->request()->data->donateur;
            $description = $this->app->request()->data->description;
            $quantite = $this->app->request()->data->quantite;

            if (empty($idCategorie) || $quantite === null || $quantite <= 0) {
                $this->app->halt(400, "Champs obligatoires.");
            }

            // Verifier que la categorie existe
            $queryCategorie = Flight::db()->prepare('SELECT id FROM bn_categorie_besoin WHERE id = ?');
            $queryCategorie->execute([$idCategorie]);
            $categorieExists = $queryCategorie->fetch();

            if (!$categorieExists) {
                $this->app->halt(400, "Categorie invalide ou inexistante.");
            }

            Don::create($idCategorie, $donateur, $description, $quantite);

            $this->app->redirect('/dons/liste');
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

    // Liste tous les dons
    public function listeDons()
    {
        $dons = DonService::getDonsWithCategories();
        $this->app->render('don/liste', ['dons' => $dons]);
    }

    // Dispatcher automatiquement les dons aux besoins
    public function dispatchDons()
    {
        try {
            $count = DonService::dispatchDons();
            $message = "Dispatch effectue : $count don(s) assigne(s) aux besoins.";
            $this->app->render('don/dispatch', ['message' => $message]);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }
}
