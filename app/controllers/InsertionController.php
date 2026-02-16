<?php
namespace app\controllers;

use app\models\Sinistre;
use app\models\SinistreBesoin;
use Throwable;

class InsertionController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

       public function insertSinistre() {
        try {
            $nombre = $this->app->request()->data->nombre_sinistres;
            $idVille = $this->app->request()->data->id_ville;

            if (empty($nombre) || empty($idVille)) {
                $this->app->halt(400, "Champs obligatoires.");
            }

            Sinistre::create($nombre, $idVille);
            $this->app->redirect('/sinistres/liste');
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

        public function insertBesoin() {
        try {
            $idSinistre = $this->app->request()->data->id_sinistre;
            $idCategorie = $this->app->request()->data->id_categorie_besoin;
            $description = $this->app->request()->data->description;
            $quantite = $this->app->request()->data->quantite;

            if (empty($idSinistre) || empty($idCategorie) || $quantite === null) {
                $this->app->halt(400, "Champs obligatoires.");
            }

            SinistreBesoin::create($idSinistre, $idCategorie, $description, $quantite);

            $this->app->redirect('/sinistres/liste');
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

}
