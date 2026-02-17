<?php
namespace app\services;
use app\models\Achat;
use Flight;

class AchatService
{
    /**
     * Récupérer les besoins restants (quantite > 0) pour faire des achats
     * @param int|null $id_ville Filtrer par ville (optionnel)
     * @return array Liste des besoins restants
     */
    public static function getBesoinsRestants($id_ville = null)
    {
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
            return $query->fetchAll();
        } else {
            $sql .= ' ORDER BY v.nom, cb.nom';
            return Flight::db()->query($sql)->fetchAll();
        }
    }

    /**
     * Récupérer un besoin avec ses détails pour le formulaire d'achat
     * @param int $id_besoin ID du besoin
     * @return array|false Besoin trouvé ou false
     */
    public static function getBesoinWithDetails($id_besoin)
    {
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
        return $query->fetch();
    }

    /**
     * Récupérer les dons en argent disponibles
     * @return array Liste des dons en argent disponibles
     */
    public static function getDonsArgentDisponibles()
    {
        $query = Flight::db()->query('
            SELECT da.id_don, da.montant_restant, d.donateur, d.date_don, v.nom as ville_nom
            FROM bn_don_argent da
            JOIN bn_don d ON da.id_don = d.id
            LEFT JOIN bn_ville v ON d.id_ville = v.id
            WHERE da.montant_restant > 0
            ORDER BY d.date_don ASC
        ');
        return $query->fetchAll();
    }

    /**
     * Récupérer les détails d'un besoin pour l'achat (avec sinistre et catégorie)
     * @param int $id_besoin ID du besoin
     * @return array|false Besoin trouvé ou false
     */
    public static function getBesoinForAchat($id_besoin)
    {
        $query = Flight::db()->prepare('
            SELECT sb.*, s.id_ville, cb.id as id_categorie
            FROM bn_sinistre_besoin sb
            JOIN bn_sinistre s ON sb.id_sinistre = s.id
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            WHERE sb.id = ?
        ');
        $query->execute([$id_besoin]);
        return $query->fetch();
    }

    /**
     * Récupérer un don en argent par ID
     * @param int $id_don_argent ID du don en argent
     * @return array|false Don trouvé ou false
     */
    public static function getDonArgent($id_don_argent)
    {
        $query = Flight::db()->prepare('
            SELECT * FROM bn_don_argent WHERE id_don = ?
        ');
        $query->execute([$id_don_argent]);
        return $query->fetch();
    }

    /**
     * Réduire la quantité d'un besoin
     * @param int $id_besoin ID du besoin
     * @param int $quantite Quantité à réduire
     * @return bool True si succès
     */
    public static function reduireBesoin($id_besoin, $quantite)
    {
        $query = Flight::db()->prepare('
            UPDATE bn_sinistre_besoin 
            SET quantite = quantite - ? 
            WHERE id = ?
        ');
        return $query->execute([$quantite, $id_besoin]);
    }

    /**
     * Réduire le montant restant d'un don en argent
     * @param int $id_don_argent ID du don en argent
     * @param float $montant Montant à réduire
     * @return bool True si succès
     */
    public static function reduireDonArgent($id_don_argent, $montant)
    {
        $query = Flight::db()->prepare('
            UPDATE bn_don_argent 
            SET montant_restant = montant_restant - ? 
            WHERE id_don = ?
        ');
        return $query->execute([$montant, $id_don_argent]);
    }

    /**
     * Traiter un achat complet (transaction)
     * @param int $id_besoin ID du besoin
     * @param int $id_don_argent ID du don en argent
     * @param int $quantite Quantité à acheter
     * @return void
     * @throws \Exception En cas d'erreur
     */
    public static function processAchat($id_besoin, $id_don_argent, $quantite)
    {
        // Récupérer le besoin
        $besoin = self::getBesoinForAchat($id_besoin);
        if (!$besoin) {
            throw new \Exception("Besoin non trouvé.");
        }

        // Vérifier que la quantité demandée ne dépasse pas le besoin
        if ($quantite > $besoin['quantite']) {
            throw new \Exception("La quantité demandée dépasse le besoin restant ({$besoin['quantite']}).");
        }

        // Vérifier si le besoin existe dans les dons nature
        if (Achat::besoinExisteDansDonsNature($besoin['id_categorie'], $quantite)) {
            throw new \Exception("Erreur : Ce besoin existe déjà dans les dons en nature disponibles. Utilisez plutôt le dispatch automatique.");
        }

        // Récupérer le don en argent
        $don = self::getDonArgent($id_don_argent);
        if (!$don) {
            throw new \Exception("Don en argent non trouvé.");
        }

        // Calculer les montants
        $frais_pourcentage = Achat::getFraisPourcentage();
        $montant_base = $quantite * $besoin['prix_unitaire'];
        $montant_avec_frais = Achat::calculerMontantAvecFrais($montant_base, $frais_pourcentage);

        // Vérifier que le don a assez d'argent
        if ($don['montant_restant'] < $montant_avec_frais) {
            throw new \Exception("Le don sélectionné n'a pas assez d'argent disponible (disponible: {$don['montant_restant']} Ar, requis: {$montant_avec_frais} Ar).");
        }

        // Transaction pour créer l'achat et mettre à jour les données
        Flight::db()->beginTransaction();

        try {
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
            self::reduireBesoin($id_besoin, $quantite);

            // Réduire le montant restant du don
            self::reduireDonArgent($id_don_argent, $montant_avec_frais);

            Flight::db()->commit();
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }
}
