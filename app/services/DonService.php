<?php
namespace app\services;
use app\models\Don;
use Flight;

class DonService
{
    /**
     * Dispatcher automatiquement les dons aux besoins
     * Parcourt les dons par ordre de date et les assigne aux besoins correspondants
     * @return int Nombre de dons assignés
     */
    public static function dispatchDons()
    {
        $dons = Don::findAll();
        $count = 0;

        foreach ($dons as $don) {
            // Chercher un besoin non satisfait de la meme categorie
            $queryBesoin = Flight::db()->prepare('
                SELECT id, quantite FROM bn_sinistre_besoin 
                WHERE id_categorie_besoin = ? AND quantite > 0
                ORDER BY id ASC LIMIT 1
            ');
            $queryBesoin->execute([$don['id_categorie_besoin']]);
            $besoin = $queryBesoin->fetch();

            if ($besoin) {
                // Calculer la quantite a assigner
                $quantiteAssignee = min($don['quantite'], $besoin['quantite']);

                // Mettre a jour le besoin
                $queryUpdate = Flight::db()->prepare('
                    UPDATE bn_sinistre_besoin 
                    SET quantite = quantite - ? 
                    WHERE id = ?
                ');
                $queryUpdate->execute([$quantiteAssignee, $besoin['id']]);

                // Supprimer le don
                Don::delete($don['id']);

                $count++;
            }
        }

        return $count;
    }

    /**
     * Recupere tous les dons avec leurs informations de categorie
     * @return array Dons enrichis avec les noms de categorie
     */
    public static function getDonsWithCategories()
    {
        $dons = Don::findAll();
        
        // Enrichir avec noms de categories
        foreach ($dons as &$don) {
            $queryCategorie = Flight::db()->prepare('SELECT nom FROM bn_categorie_besoin WHERE id = ?');
            $queryCategorie->execute([$don['id_categorie_besoin']]);
            $categorie = $queryCategorie->fetch();
            $don['categorie_nom'] = $categorie['nom'] ?? 'Inconnue';
        }

        return $dons;
    }
}
