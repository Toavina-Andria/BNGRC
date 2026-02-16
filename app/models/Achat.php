<?php
namespace app\models;
use Flight;

/**
 * Modèle pour gérer les achats de besoins avec des dons en argent
 */
class Achat
{
    /**
     * Créer un achat
     * @param int $id_ville ID de la ville concernée
     * @param int $id_besoin ID du besoin à acheter
     * @param int $id_don_argent ID du don en argent utilisé
     * @param int $quantite Quantité achetée
     * @param float $prix_unitaire Prix unitaire du besoin
     * @param float $frais_pourcentage Pourcentage de frais d'achat
     * @return bool True si succès, false sinon
     */
    public static function create($id_ville, $id_besoin, $id_don_argent, $quantite, $prix_unitaire, $frais_pourcentage)
    {
        $montant_total = $quantite * $prix_unitaire;
        $montant_avec_frais = $montant_total * (1 + ($frais_pourcentage / 100));

        $query = Flight::db()->prepare('
            INSERT INTO bn_achat 
            (id_ville, id_besoin, id_don_argent, quantite, prix_unitaire, montant_total, frais_pourcentage, montant_avec_frais)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        return $query->execute([
            $id_ville, 
            $id_besoin, 
            $id_don_argent, 
            $quantite, 
            $prix_unitaire, 
            $montant_total, 
            $frais_pourcentage, 
            $montant_avec_frais
        ]);
    }

    /**
     * Récupérer tous les achats avec détails
     * @param int|null $id_ville Filtrer par ville (optionnel)
     * @return array Liste des achats
     */
    public static function findAll($id_ville = null)
    {
        $sql = '
            SELECT 
                a.*,
                v.nom as ville_nom,
                r.nom as region_nom,
                cb.nom as categorie_nom,
                sb.description as besoin_description,
                d.donateur as donateur_nom
            FROM bn_achat a
            JOIN bn_ville v ON a.id_ville = v.id
            JOIN bn_region r ON v.id_region = r.id
            JOIN bn_sinistre_besoin sb ON a.id_besoin = sb.id
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            JOIN bn_don d ON a.id_don_argent = d.id
        ';

        if ($id_ville !== null) {
            $sql .= ' WHERE a.id_ville = ?';
            $query = Flight::db()->prepare($sql . ' ORDER BY a.date_achat DESC');
            $query->execute([$id_ville]);
            return $query->fetchAll();
        }

        $sql .= ' ORDER BY a.date_achat DESC';
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Récupérer un achat par ID
     * @param int $id ID de l'achat
     * @return array|false Achat trouvé ou false
     */
    public static function findById($id)
    {
        $query = Flight::db()->prepare('
            SELECT 
                a.*,
                v.nom as ville_nom,
                r.nom as region_nom,
                cb.nom as categorie_nom,
                sb.description as besoin_description,
                d.donateur as donateur_nom
            FROM bn_achat a
            JOIN bn_ville v ON a.id_ville = v.id
            JOIN bn_region r ON v.id_region = r.id
            JOIN bn_sinistre_besoin sb ON a.id_besoin = sb.id
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            JOIN bn_don d ON a.id_don_argent = d.id
            WHERE a.id = ?
        ');
        $query->execute([$id]);
        return $query->fetch();
    }

    /**
     * Vérifier si un besoin existe déjà dans les dons nature disponibles
     * @param int $id_categorie ID de la catégorie du besoin
     * @param int $quantite_demandee Quantité demandée
     * @return bool True si le don existe et est suffisant, false sinon
     */
    public static function besoinExisteDansDonsNature($id_categorie, $quantite_demandee)
    {
        $query = Flight::db()->prepare('
            SELECT SUM(dn.quantite) as quantite_disponible
            FROM bn_don_nature dn
            WHERE dn.id_categorie_besoin = ? AND dn.quantite > 0
        ');
        $query->execute([$id_categorie]);
        $result = $query->fetch();

        return ($result && $result['quantite_disponible'] >= $quantite_demandee);
    }

    /**
     * Obtenir le pourcentage de frais d'achat configuré
     * @return float Pourcentage de frais
     */
    public static function getFraisPourcentage()
    {
        $query = Flight::db()->query('SELECT frais_pourcentage FROM bn_config_achat LIMIT 1');
        $result = $query->fetch();
        return $result ? floatval($result['frais_pourcentage']) : 10.00;
    }

    /**
     * Calculer le montant total avec frais
     * @param float $montant_base Montant de base
     * @param float $frais_pourcentage Pourcentage de frais
     * @return float Montant total avec frais
     */
    public static function calculerMontantAvecFrais($montant_base, $frais_pourcentage)
    {
        return $montant_base * (1 + ($frais_pourcentage / 100));
    }
}
