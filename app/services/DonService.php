<?php
namespace app\services;
use app\models\Don;
use Flight;

class DonService
{
    /**
     * ============================================================================
     * TODO FUTUR : LOGIQUE DE DISPATCH TEMPORAIRE - À AMÉLIORER
     * ============================================================================
     * 
     * ACTUELLEMENT : Distribution ALÉATOIRE des dons
     * Cette implémentation est simplifiée et ne respecte PAS les règles métier
     * 
     * À IMPLÉMENTER PLUS TARD :
     * - Dispatch par ordre chronologique (date de réception des dons)
     * - Priorisation selon la ville ciblée
     * - Matching dons nature <-> besoins de même catégorie
     * - Utilisation intelligente des dons argent
     * - Calcul précis des montants et quantités
     * 
     * ============================================================================
     */
    public static function dispatchDons()
    {
        $result = [
            'dons_nature_traites' => 0,
            'dons_argent_utilises' => 0,
            'montant_argent_utilise' => 0,
            'besoins_satisfaits' => 0,
            'details' => []
        ];

        try {
            Flight::db()->beginTransaction();

            // ========================================================================
            // LOGIQUE TEMPORAIRE : Attribution aléatoire des dons nature
            // TODO : Implémenter le matching par catégorie et ordre chronologique
            // ========================================================================
            
            $dons_nature = Flight::db()->query('
                SELECT dn.*, d.date_don, cb.nom as categorie_nom, v.nom as ville_nom
                FROM bn_don_nature dn
                JOIN bn_don d ON dn.id_don = d.id
                JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
                LEFT JOIN bn_ville v ON d.id_ville = v.id
                WHERE dn.quantite > 0
                ORDER BY RAND()
                LIMIT 20
            ')->fetchAll();

            foreach ($dons_nature as $don) {
                // Récupérer un besoin aléatoire (même catégorie)
                $query_besoin = Flight::db()->prepare('
                    SELECT sb.id, sb.quantite, v.nom as ville_nom
                    FROM bn_sinistre_besoin sb
                    JOIN bn_sinistre s ON sb.id_sinistre = s.id
                    JOIN bn_ville v ON s.id_ville = v.id
                    WHERE sb.id_categorie_besoin = ? AND sb.quantite > 0
                    ORDER BY RAND()
                    LIMIT 1
                ');
                $query_besoin->execute([$don['id_categorie_besoin']]);
                $besoin = $query_besoin->fetch();

                if ($besoin) {
                    $quantite_assignee = min($don['quantite'], $besoin['quantite']);
                    
                    // Réduire le besoin
                    Flight::db()->prepare('
                        UPDATE bn_sinistre_besoin 
                        SET quantite = quantite - ? 
                        WHERE id = ?
                    ')->execute([$quantite_assignee, $besoin['id']]);

                    // Réduire le don
                    Flight::db()->prepare('
                        UPDATE bn_don_nature 
                        SET quantite = quantite - ?
                        WHERE id = ?
                    ')->execute([$quantite_assignee, $don['id']]);

                    $result['dons_nature_traites']++;
                    $result['details'][] = [
                        'type' => 'nature',
                        'categorie' => $don['categorie_nom'],
                        'quantite' => $quantite_assignee,
                        'ville' => $besoin['ville_nom']
                    ];
                }
            }

            // ========================================================================
            // LOGIQUE TEMPORAIRE : Attribution aléatoire des dons en argent
            // TODO : Implémenter la logique de couverture optimale des besoins
            // ========================================================================
            
            $dons_argent = Flight::db()->query('
                SELECT da.*, v.nom as ville_nom
                FROM bn_don_argent da
                JOIN bn_don d ON da.id_don = d.id
                LEFT JOIN bn_ville v ON d.id_ville = v.id
                WHERE da.montant_restant > 0
                ORDER BY RAND()
                LIMIT 10
            ')->fetchAll();

            foreach ($dons_argent as $don) {
                $montant_disponible = $don['montant_restant'];
                
                // Récupérer quelques besoins aléatoires
                $besoins = Flight::db()->query('
                    SELECT sb.id, sb.quantite, sb.prix_unitaire, 
                           cb.nom as categorie_nom, v.nom as ville_nom
                    FROM bn_sinistre_besoin sb
                    JOIN bn_sinistre s ON sb.id_sinistre = s.id
                    JOIN bn_ville v ON s.id_ville = v.id
                    JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                    WHERE sb.quantite > 0
                    ORDER BY RAND()
                    LIMIT 5
                ')->fetchAll();

                foreach ($besoins as $besoin) {
                    if ($montant_disponible <= 0) break;
                    
                    $montant_besoin = $besoin['quantite'] * $besoin['prix_unitaire'];
                    $montant_a_utiliser = min($montant_disponible, $montant_besoin);
                    $quantite_satisfaite = floor($montant_a_utiliser / $besoin['prix_unitaire']);

                    if ($quantite_satisfaite > 0) {
                        Flight::db()->prepare('
                            UPDATE bn_sinistre_besoin 
                            SET quantite = quantite - ? 
                            WHERE id = ?
                        ')->execute([$quantite_satisfaite, $besoin['id']]);

                        $montant_reel = $quantite_satisfaite * $besoin['prix_unitaire'];
                        $montant_disponible -= $montant_reel;
                        $result['montant_argent_utilise'] += $montant_reel;
                        
                        $result['details'][] = [
                            'type' => 'argent',
                            'montant' => $montant_reel,
                            'quantite' => $quantite_satisfaite,
                            'categorie' => $besoin['categorie_nom'],
                            'ville' => $besoin['ville_nom']
                        ];
                    }
                }

                // Mettre à jour le montant restant
                if ($montant_disponible < $don['montant_restant']) {
                    Flight::db()->prepare('
                        UPDATE bn_don_argent 
                        SET montant_restant = ?
                        WHERE id_don = ?
                    ')->execute([$montant_disponible, $don['id_don']]);

                    $result['dons_argent_utilises']++;
                }
            }

            // Compter les besoins complètement satisfaits
            $result['besoins_satisfaits'] = Flight::db()->query('
                SELECT COUNT(*) as count 
                FROM bn_sinistre_besoin 
                WHERE quantite = 0
            ')->fetch()['count'] ?? 0;

            Flight::db()->commit();
            return $result;

        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }

    /**
     * Récupérer tous les dons avec leurs informations de catégorie
     * @return array Dons enrichis avec les noms de catégorie
     */
    public static function getDonsWithDetails()
    {
        return Don::findAll();
    }

    /**
     * ============================================================================
     * TODO FUTUR : Implémenter une vraie simulation
     * ============================================================================
     * Actuellement : utilise la même logique aléatoire en transaction rollback
     * À améliorer : prévisualisation détaillée avant validation
     * ============================================================================
     */
    public static function simulateDispatch()
    {
        try {
            Flight::db()->beginTransaction();
            $result = self::dispatchDons();
            Flight::db()->rollBack(); // Annuler tous les changements
            $result['simulation'] = true;
            return $result;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }
}

