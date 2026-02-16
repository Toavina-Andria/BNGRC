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

       // affiche le formulaire d'ajout de sinistre
    public function showSinistreForm() {
        $this->app->render('sinistre/form');
    }

    public function insertSinistre() {
        try {
            $nombre = $this->app->request()->data->nombre_sinistres;
            $idVille = $this->app->request()->data->id_ville;

            if (empty($nombre) || empty($idVille)) {
                $this->app->halt(400, "Champs obligatoires.");
            }

            // Vérifier que la ville existe
            $queryVille = \Flight::db()->prepare('SELECT id FROM bn_ville WHERE id = ?');
            $queryVille->execute([$idVille]);
            $villeExists = $queryVille->fetch();

            if (!$villeExists) {
                $this->app->halt(400, "Ville invalide ou inexistante.");
            }

            Sinistre::create($nombre, $idVille);
            
            // Récupérer l'id du dernier sinistre inséré
            $queryLast = \Flight::db()->prepare('SELECT id FROM bn_sinistre ORDER BY id DESC LIMIT 1');
            $queryLast->execute();
            $lastSinistre = $queryLast->fetch();
            
            $sinistreId = $lastSinistre['id'] ?? null;

            
            // Rediriger vers la page d'insertion de besoin avec l'id du sinistre
            $this->app->redirect('/sinistres/besoins/insert?sinistre_id=' . $sinistreId);
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

        // affiche formulaire d'ajout de besoin lié à un sinistre
    public function showBesoinForm() {
        $sinistreId = $_GET['sinistre_id'] ?? null;
        
        if (empty($sinistreId)) {
            $this->app->halt(400, "ID sinistre manquant.");
        }
        
        // Vérifier que le sinistre existe
        $querySinistre = \Flight::db()->prepare('SELECT id FROM bn_sinistre WHERE id = ?');
        $querySinistre->execute([$sinistreId]);
        $sinistre = $querySinistre->fetch();
        
        if (!$sinistre) {
            $this->app->halt(404, "Sinistre non trouvé.");
        }
        
        $this->app->render('sinistre/besoin_form', ['sinistre_id' => $sinistreId]);
    }

    public function insertBesoin() {
        try {
            $idSinistre = $this->app->request()->data->id_sinistre;
            $idCategorie = $this->app->request()->data->id_categorie_besoin;
            $description = $this->app->request()->data->description;
            $quantite = $this->app->request()->data->quantite;
            $action = $this->app->request()->data->action ?? 'finish';

            if (empty($idSinistre) || empty($idCategorie) || $quantite === null) {
                $this->app->halt(400, "Champs obligatoires.");
            }

            // Vérifier que le sinistre existe
            $querySinistre = \Flight::db()->prepare('SELECT id FROM bn_sinistre WHERE id = ?');
            $querySinistre->execute([$idSinistre]);
            $sinistreExists = $querySinistre->fetch();

            if (!$sinistreExists) {
                $this->app->halt(400, "Sinistre invalide ou inexistant.");
            }

            // Vérifier que la catégorie existe
            $queryCategorie = \Flight::db()->prepare('SELECT id FROM bn_categorie_besoin WHERE id = ?');
            $queryCategorie->execute([$idCategorie]);
            $categorieExists = $queryCategorie->fetch();

            if (!$categorieExists) {
                $this->app->halt(400, "Catégorie invalide ou inexistante.");
            }

            SinistreBesoin::create($idSinistre, $idCategorie, $description, $quantite);

            // Vérifier l'action demandée
            if ($action === 'add_another') {
                // Rediriger vers le formulaire d'ajout d'un autre besoin
                $this->app->redirect('/sinistres/besoins/insert?sinistre_id=' . $idSinistre);
            } else {
                // Rediriger vers le dashboard
                $this->app->redirect('/sinistres/liste');
            }
        } catch (Throwable $e) {
            $this->app->halt(500, "Erreur : " . $e->getMessage());
        }
    }

}
