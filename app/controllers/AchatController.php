<?php
namespace app\controllers;
use app\models\Achat;
use app\models\SinistreBesoin;
use app\models\Ville;
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
            
            // Requête pour récupérer les besoins restants (quantite > 0)
            $sql = '
                SELECT 
                    sb.id,
                    sb.quantite,
                    sb.prix_unitaire,
                    sb.description,
                    cb.nom as categorie_nom,
                    cb.id as id_categorie,
                    v.id as id_ville,
                    v.nom as ville_nom,
                    r.nom as region_nom,
                    s.nombre_sinistres,
                    (sb.quantite * sb.prix_unitaire) as montant_total
                FROM bn_sinistre_besoin sb
                JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                JOIN bn_sinistre s ON sb.id_sinistre = s.id
                JOIN bn_ville v ON s.id_ville = v.id
                JOIN bn_region r ON v.id_region = r.id
                WHERE sb.quantite > 0
            ';

            if ($id_ville) {
                $sql .= ' AND v.id = ?';
                $query = Flight::db()->prepare($sql . ' ORDER BY v.nom, cb.nom');
                $query->execute([$id_ville]);
                $besoins = $query->fetchAll();
            } else {
                $sql .= ' ORDER BY v.nom, cb.nom';
                $besoins = Flight::db()->query($sql)->fetchAll();
            }

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

            // Récupérer le besoin avec ses détails
            $query = Flight::db()->prepare('
                SELECT 
                    sb.id,
                    sb.quantite,
                    sb.prix_unitaire,
                    sb.description,
                    cb.nom as categorie_nom,
                    cb.id as id_categorie,
                    v.id as id_ville,
                    v.nom as ville_nom,
                    r.nom as region_nom,
                    s.nombre_sinistres,
                    (sb.quantite * sb.prix_unitaire) as montant_total
                FROM bn_sinistre_besoin sb
                JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                JOIN bn_sinistre s ON sb.id_sinistre = s.id
                JOIN bn_ville v ON s.id_ville = v.id
                JOIN bn_region r ON v.id_region = r.id
                WHERE sb.id = ? AND sb.quantite > 0
            ');
            $query->execute([$id_besoin]);
            $besoin = $query->fetch();

            if (!$besoin) {
                $this->app->halt(404, "Besoin non trouvé ou déjà satisfait.");
            }

            // Vérifier si le besoin existe dans les dons nature disponibles
            $existe_dans_dons = Achat::besoinExisteDansDonsNature(
                $besoin['id_categorie'], 
                $besoin['quantite']
            );

            // Récupérer les dons en argent disponibles
            $dons_argent = Flight::db()->query('
                SELECT da.id_don, da.montant_restant, d.donateur, d.date_don, v.nom as ville_nom
                FROM bn_don_argent da
                JOIN bn_don d ON da.id_don = d.id
                LEFT JOIN bn_ville v ON d.id_ville = v.id
                WHERE da.montant_restant > 0
                ORDER BY d.date_don ASC
            ')->fetchAll();

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

            // Récupérer le besoin
            $query_besoin = Flight::db()->prepare('
                SELECT sb.*, s.id_ville, cb.id as id_categorie
                FROM bn_sinistre_besoin sb
                JOIN bn_sinistre s ON sb.id_sinistre = s.id
                JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                WHERE sb.id = ?
            ');
            $query_besoin->execute([$id_besoin]);
            $besoin = $query_besoin->fetch();

            if (!$besoin) {
                $this->app->halt(404, "Besoin non trouvé.");
            }

            // Vérifier que la quantité demandée ne dépasse pas le besoin
            if ($quantite > $besoin['quantite']) {
                $this->app->halt(400, "La quantité demandée dépasse le besoin restant ({$besoin['quantite']}).");
            }

            // Vérifier si le besoin existe dans les dons nature
            if (Achat::besoinExisteDansDonsNature($besoin['id_categorie'], $quantite)) {
                $this->app->halt(400, "Erreur : Ce besoin existe déjà dans les dons en nature disponibles. Utilisez plutôt le dispatch automatique.");
            }

            // Récupérer le don en argent
            $query_don = Flight::db()->prepare('
                SELECT * FROM bn_don_argent WHERE id_don = ?
            ');
            $query_don->execute([$id_don_argent]);
            $don = $query_don->fetch();

            if (!$don) {
                $this->app->halt(404, "Don en argent non trouvé.");
            }

            // Calculer les montants
            $frais_pourcentage = Achat::getFraisPourcentage();
            $montant_base = $quantite * $besoin['prix_unitaire'];
            $montant_avec_frais = Achat::calculerMontantAvecFrais($montant_base, $frais_pourcentage);

            // Vérifier que le don a assez d'argent
            if ($don['montant_restant'] < $montant_avec_frais) {
                $this->app->halt(400, "Le don sélectionné n'a pas assez d'argent disponible (disponible: {$don['montant_restant']} Ar, requis: {$montant_avec_frais} Ar).");
            }

            // Transaction pour créer l'achat et mettre à jour les données
            Flight::db()->beginTransaction();

            // Créer l'achat
            Achat::create(
                $besoin['id_ville'],
                $id_besoin,
                $id_don_argent,
                $quantite,
                $besoin['prix_unitaire'],
                $frais_pourcentage
            );

            // Réduire la quantité du besoin
            $query_update_besoin = Flight::db()->prepare('
                UPDATE bn_sinistre_besoin 
                SET quantite = quantite - ? 
                WHERE id = ?
            ');
            $query_update_besoin->execute([$quantite, $id_besoin]);

            // Réduire le montant restant du don
            $query_update_don = Flight::db()->prepare('
                UPDATE bn_don_argent 
                SET montant_restant = montant_restant - ? 
                WHERE id_don = ?
            ');
            $query_update_don->execute([$montant_avec_frais, $id_don_argent]);

            Flight::db()->commit();

            // Rediriger vers la liste des achats
            $this->app->redirect('/achats/liste');

        } catch (Throwable $e) {
            Flight::db()->rollBack();
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
