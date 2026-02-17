<?php
namespace app\services;
use app\models\Don;
use app\models\SinistreBesoin;
use app\models\Ville;
use Flight;

class DispatcherService
{
    /**
     * Dispatche automatiquement les dons aux besoins selon le principe FIFO (First In, First Out)
     * Priorité aux besoins les plus anciens et aux dons les plus anciens
     * @return array Résultat du dispatch avec statistiques
     */
    public static function dispatchDons()
    {
        try {
            Flight::db()->beginTransaction();
            $result = self::executeDispatchFIFO();
            Flight::db()->commit();
            return $result;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }

    /**
     * Exécute le dispatch FIFO sans gérer les transactions
     * @return array Résultat du dispatch avec statistiques
     */
    public static function executeDispatchFIFO()
    {
        $result = self::initializeResult();

        // Dispatcher les dons en nature
        $natureResult = self::dispatchInKindDonations();
        $result = array_merge($result, $natureResult);

        // Dispatcher les dons en argent
        $argentResult = self::dispatchMoneyDonations();
        $result = array_merge($result, $argentResult);

        // Compter les besoins satisfaits
        $result['besoins_satisfaits'] = self::countSatisfiedNeeds();
        
        // Ajouter la méthode utilisée
        $result['methode'] = 'ordre';

        return $result;
    }

    /**
     * Initialise le tableau de résultats
     * @return array
     */
    private static function initializeResult()
    {
        return [
            'dons_nature_traites' => 0,
            'dons_argent_utilises' => 0,
            'montant_argent_utilise' => 0,
            'besoins_satisfaits' => 0,
            'details' => [],
            'methode' => 'ordre'
        ];
    }

    /**
     * Dispatche les dons en nature aux besoins (FIFO: plus anciens besoins prioritaires)
     * @return array Résultats du dispatch des dons nature
     */
    private static function dispatchInKindDonations()
    {
        $result = [
            'dons_nature_traites' => 0,
            'details' => []
        ];

        $dons_nature = self::getAvailableInKindDonations();

        foreach ($dons_nature as $don) {
            $besoin = self::findMatchingNeedForInKindDonation($don);

            if ($besoin) {
                $dispatchDetail = self::processInKindDonationDispatch($don, $besoin);
                $result['dons_nature_traites']++;
                $result['details'][] = $dispatchDetail;
            }
        }

        return $result;
    }

    /**
     * Récupère les dons en nature disponibles (plus anciens d'abord)
     * @return array
     */
    private static function getAvailableInKindDonations()
    {
        return Flight::db()->query('
            SELECT dn.*, d.date_don, cb.nom as categorie_nom, v.nom as ville_nom
            FROM bn_don_nature dn
            JOIN bn_don d ON dn.id_don = d.id
            JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
            LEFT JOIN bn_ville v ON d.id_ville = v.id
            WHERE dn.quantite > 0
            ORDER BY d.date_don ASC, dn.id ASC
            LIMIT 20
        ')->fetchAll();
    }

    /**
     * Trouve un besoin correspondant pour un don en nature (priorité aux plus anciens)
     * @param array $don Don en nature
     * @return array|null Besoin trouvé ou null
     */
    private static function findMatchingNeedForInKindDonation($don)
    {
        $query = Flight::db()->prepare('
            SELECT sb.id, sb.quantite, v.nom as ville_nom
            FROM bn_sinistre_besoin sb
            JOIN bn_sinistre s ON sb.id_sinistre = s.id
            JOIN bn_ville v ON s.id_ville = v.id
            WHERE sb.id_categorie_besoin = ? AND sb.quantite > 0
            ORDER BY sb.id ASC
            LIMIT 1
        ');
        $query->execute([$don['id_categorie_besoin']]);
        return $query->fetch();
    }

    /**
     * Traite le dispatch d'un don en nature vers un besoin
     * @param array $don Don en nature
     * @param array $besoin Besoin cible
     * @return array Détail du dispatch
     */
    private static function processInKindDonationDispatch($don, $besoin)
    {
        $quantite_assignee = min($don['quantite'], $besoin['quantite']);

        // Réduire le besoin
        self::reduceNeedQuantity($besoin['id'], $quantite_assignee);

        // Réduire le don
        self::reduceInKindDonationQuantity($don['id'], $quantite_assignee);

        return [
            'type' => 'nature',
            'categorie' => $don['categorie_nom'],
            'quantite' => $quantite_assignee,
            'ville' => $besoin['ville_nom']
        ];
    }

    /**
     * Dispatche les dons en argent aux besoins (FIFO: plus anciens besoins prioritaires)
     * @return array Résultats du dispatch des dons argent
     */
    private static function dispatchMoneyDonations()
    {
        $result = [
            'dons_argent_utilises' => 0,
            'montant_argent_utilise' => 0,
            'details' => []
        ];

        $dons_argent = self::getAvailableMoneyDonations();

        foreach ($dons_argent as $don) {
            $dispatchResult = self::processMoneyDonationDispatch($don);
            if ($dispatchResult['montant_utilise'] > 0) {
                $result['dons_argent_utilises']++;
                $result['montant_argent_utilise'] += $dispatchResult['montant_utilise'];
                $result['details'] = array_merge($result['details'], $dispatchResult['details']);
            }
        }

        return $result;
    }

    /**
     * Récupère les dons en argent disponibles (plus anciens d'abord)
     * @return array
     */
    private static function getAvailableMoneyDonations()
    {
        return Flight::db()->query('
            SELECT da.*, v.nom as ville_nom
            FROM bn_don_argent da
            JOIN bn_don d ON da.id_don = d.id
            LEFT JOIN bn_ville v ON d.id_ville = v.id
            WHERE da.montant_restant > 0
            ORDER BY d.date_don ASC, da.id_don ASC
            LIMIT 10
        ')->fetchAll();
    }

    /**
     * Traite le dispatch d'un don en argent
     * @param array $don Don en argent
     * @return array Résultat du dispatch
     */
    private static function processMoneyDonationDispatch($don)
    {
        $montant_disponible = $don['montant_restant'];
        $montant_initial = $montant_disponible;
        $details = [];

        $besoins = self::getPriorityNeedsForMoneyDispatch();

        foreach ($besoins as $besoin) {
            if ($montant_disponible <= 0) {
                break;
            }

            $dispatchDetail = self::calculateMoneyDispatchForNeed($besoin, $montant_disponible);
            if ($dispatchDetail) {
                $montant_disponible -= $dispatchDetail['montant_reel'];
                $details[] = $dispatchDetail['detail'];
            }
        }

        $montant_utilise = $montant_initial - $montant_disponible;

        // Mettre à jour le montant restant si utilisé
        if ($montant_utilise > 0) {
            self::updateMoneyDonationBalance($don['id_don'], $montant_disponible);
        }

        return [
            'montant_utilise' => $montant_utilise,
            'details' => $details
        ];
    }

    /**
     * Récupère des besoins prioritaires pour le dispatch d'argent (plus anciens d'abord)
     * @return array
     */
    private static function getPriorityNeedsForMoneyDispatch()
    {
        return Flight::db()->query('
            SELECT sb.id, sb.quantite, sb.prix_unitaire,
                   cb.nom as categorie_nom, v.nom as ville_nom
            FROM bn_sinistre_besoin sb
            JOIN bn_sinistre s ON sb.id_sinistre = s.id
            JOIN bn_ville v ON s.id_ville = v.id
            JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
            WHERE sb.quantite > 0
            ORDER BY sb.id ASC
            LIMIT 5
        ')->fetchAll();
    }

    /**
     * Calcule le dispatch d'argent pour un besoin spécifique
     * @param array $besoin Besoin cible
     * @param float $montant_disponible Montant disponible
     * @return array|null Détail du dispatch ou null
     */
    private static function calculateMoneyDispatchForNeed($besoin, $montant_disponible)
    {
        $montant_besoin = $besoin['quantite'] * $besoin['prix_unitaire'];
        $montant_a_utiliser = min($montant_disponible, $montant_besoin);
        $quantite_satisfaite = floor($montant_a_utiliser / $besoin['prix_unitaire']);

        if ($quantite_satisfaite > 0) {
            $montant_reel = $quantite_satisfaite * $besoin['prix_unitaire'];

            // Réduire le besoin
            self::reduceNeedQuantity($besoin['id'], $quantite_satisfaite);

            return [
                'montant_reel' => $montant_reel,
                'detail' => [
                    'type' => 'argent',
                    'montant' => $montant_reel,
                    'quantite' => $quantite_satisfaite,
                    'categorie' => $besoin['categorie_nom'],
                    'ville' => $besoin['ville_nom']
                ]
            ];
        }

        return null;
    }

    /**
     * Compte le nombre de besoins complètement satisfaits
     * @return int
     */
    private static function countSatisfiedNeeds()
    {
        $result = Flight::db()->query('
            SELECT COUNT(*) as count
            FROM bn_sinistre_besoin
            WHERE quantite = 0
        ')->fetch();
        return $result['count'] ?? 0;
    }

    /**
     * Réduit la quantité d'un besoin (sans jamais passer en négatif)
     * @param int $besoinId ID du besoin
     * @param int $quantite Quantité à réduire
     */
    private static function reduceNeedQuantity($besoinId, $quantite)
    {
        Flight::db()->prepare('
            UPDATE bn_sinistre_besoin
            SET quantite = GREATEST(0, quantite - ?)
            WHERE id = ?
        ')->execute([$quantite, $besoinId]);
    }

    /**
     * Réduit la quantité d'un don en nature
     * @param int $donNatureId ID du don en nature
     * @param int $quantite Quantité à réduire
     */
    private static function reduceInKindDonationQuantity($donNatureId, $quantite)
    {
        Flight::db()->prepare('
            UPDATE bn_don_nature
            SET quantite = GREATEST(0, quantite - ?)
            WHERE id = ?
        ')->execute([$quantite, $donNatureId]);
    }

    /**
     * Met à jour le solde d'un don en argent
     * @param int $donId ID du don
     * @param float $nouveauSolde Nouveau solde
     */
    private static function updateMoneyDonationBalance($donId, $nouveauSolde)
    {
        Flight::db()->prepare('
            UPDATE bn_don_argent
            SET montant_restant = GREATEST(0, ?)
            WHERE id_don = ?
        ')->execute([$nouveauSolde, $donId]);
    }
}