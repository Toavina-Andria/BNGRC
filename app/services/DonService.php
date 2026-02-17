<?php
namespace app\services;
use app\models\Don;
use app\services\DispatcherService;
use Flight;

class DonService
{
    /**
     * Dispatcher les dons selon une méthode choisie
     * @param string $methode 'quantite' | 'proportionnalite' | 'ordre'
     * @return array Résultats du dispatch
     */
    public static function dispatchDons($methode = 'quantite')
    {
        try {
            Flight::db()->beginTransaction();
            
            if ($methode == 'ordre') {
                $result = DispatcherService::executeDispatchFIFO();
            } elseif ($methode == 'proportionnalite') {
                $result = self::executeDispatchParProportionnalite();
            } else {
                $result = self::executeDispatchParQuantite();
            }
            
            Flight::db()->commit();
            return $result;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }


    /**
     * Dispatcher les dons en PRIORITÉ AUX PETITS BESOINS (par quantité croissante)
     * Logique pure sans gestion de transaction
     * @return array Résultats du dispatch
     */
    private static function executeDispatchParQuantite()
    {
        $result = [
            'dons_nature_traites' => 0,
            'dons_argent_utilises' => 0,
            'montant_argent_utilise' => 0,
            'besoins_satisfaits' => 0,
            'details' => [],
            'methode' => 'quantite'
        ];

        // Distribution dons nature par quantité croissante
            $dons_nature = Flight::db()->query('
                SELECT dn.*, d.date_don, cb.nom as categorie_nom, v.nom as ville_nom
                FROM bn_don_nature dn
                JOIN bn_don d ON dn.id_don = d.id
                JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
                LEFT JOIN bn_ville v ON d.id_ville = v.id
                WHERE dn.quantite > 0
                ORDER BY d.date_don ASC
            ')->fetchAll();

            foreach ($dons_nature as $don) {
                // Récupérer les besoins de la même catégorie, ordonnés par quantité croissante
                $query_besoins = Flight::db()->prepare('
                    SELECT sb.id, sb.quantite, v.nom as ville_nom
                    FROM bn_sinistre_besoin sb
                    JOIN bn_sinistre s ON sb.id_sinistre = s.id
                    JOIN bn_ville v ON s.id_ville = v.id
                    WHERE sb.id_categorie_besoin = ? AND sb.quantite > 0
                    ORDER BY sb.quantite ASC
                ');
                $query_besoins->execute([$don['id_categorie_besoin']]);
                $besoins = $query_besoins->fetchAll();

                $quantite_restante = $don['quantite'];

                foreach ($besoins as $besoin) {
                    if ($quantite_restante <= 0) break;

                    $quantite_assignee = min($quantite_restante, $besoin['quantite']);
                    
                    // Réduire le besoin
                    Flight::db()->prepare('
                        UPDATE bn_sinistre_besoin 
                        SET quantite = quantite - ? 
                        WHERE id = ?
                    ')->execute([$quantite_assignee, $besoin['id']]);

                    $quantite_restante -= $quantite_assignee;

                    $result['dons_nature_traites']++;
                    $result['details'][] = [
                        'type' => 'nature',
                        'categorie' => $don['categorie_nom'],
                        'quantite' => $quantite_assignee,
                        'ville' => $besoin['ville_nom']
                    ];
                }

                // Réduire le don
                if ($quantite_restante < $don['quantite']) {
                    Flight::db()->prepare('
                        UPDATE bn_don_nature 
                        SET quantite = ?
                        WHERE id = ?
                    ')->execute([$quantite_restante, $don['id']]);
                }
            }

            // Distribution argent par quantité croissante
            $dons_argent = Flight::db()->query('
                SELECT da.*, v.nom as ville_nom, d.date_don
                FROM bn_don_argent da
                JOIN bn_don d ON da.id_don = d.id
                LEFT JOIN bn_ville v ON d.id_ville = v.id
                WHERE da.montant_restant > 0
                ORDER BY d.date_don ASC
            ')->fetchAll();

            foreach ($dons_argent as $don) {
                $montant_disponible = $don['montant_restant'];
                
                $besoins = Flight::db()->query('
                    SELECT sb.id, sb.quantite, sb.prix_unitaire, 
                           cb.nom as categorie_nom, v.nom as ville_nom
                    FROM bn_sinistre_besoin sb
                    JOIN bn_sinistre s ON sb.id_sinistre = s.id
                    JOIN bn_ville v ON s.id_ville = v.id
                    JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                    WHERE sb.quantite > 0
                    ORDER BY sb.quantite ASC
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

                if ($montant_disponible < $don['montant_restant']) {
                    Flight::db()->prepare('
                        UPDATE bn_don_argent 
                        SET montant_restant = ?
                        WHERE id_don = ?
                    ')->execute([$montant_disponible, $don['id_don']]);

                    $result['dons_argent_utilises']++;
                }
            }

            $result['besoins_satisfaits'] = Flight::db()->query('
                SELECT COUNT(*) as count 
                FROM bn_sinistre_besoin 
                WHERE quantite = 0
            ')->fetch()['count'] ?? 0;

            return $result;
        }

        /**
         * Dispatcher les dons de manière PROPORTIONNELLE
     * Chaque besoin reçoit une part proportionnelle des dons disponibles
     * @return array Résultats du dispatch
     */
    private static function executeDispatchParProportionnalite()
    {
        $result = [
            'dons_nature_traites' => 0,
            'dons_argent_utilises' => 0,
            'montant_argent_utilise' => 0,
            'besoins_satisfaits' => 0,
            'details' => [],
            'methode' => 'proportionnalite'
        ];

        // Distribution PROPORTIONNELLE dons nature par catégorie
        $categories = Flight::db()->query('
                SELECT DISTINCT id_categorie_besoin
                FROM bn_sinistre_besoin
                WHERE quantite > 0
            ')->fetchAll();

            foreach ($categories as $cat) {
                $id_categorie = $cat['id_categorie_besoin'];

                // Total des besoins pour cette catégorie
                $total_besoins = Flight::db()->prepare('
                    SELECT SUM(quantite) as total
                    FROM bn_sinistre_besoin
                    WHERE id_categorie_besoin = ? AND quantite > 0
                ');
                $total_besoins->execute([$id_categorie]);
                $total = $total_besoins->fetch()['total'] ?? 0;

                if ($total <= 0) continue;

                // Total des dons pour cette catégorie
                $total_dons = Flight::db()->prepare('
                    SELECT SUM(dn.quantite) as total
                    FROM bn_don_nature dn
                    WHERE dn.id_categorie_besoin = ? AND dn.quantite > 0
                ');
                $total_dons->execute([$id_categorie]);
                $dons_total = $total_dons->fetch()['total'] ?? 0;

                if ($dons_total <= 0) continue;

                // Ratio de distribution
                $ratio = $dons_total / $total;

                // Récupérer les dons de cette catégorie
                $dons_nature = Flight::db()->prepare('
                    SELECT dn.*, cb.nom as categorie_nom
                    FROM bn_don_nature dn
                    JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
                    WHERE dn.id_categorie_besoin = ? AND dn.quantite > 0
                ');
                $dons_nature->execute([$id_categorie]);
                $dons = $dons_nature->fetchAll();

                // Récupérer les besoins de cette catégorie
                $besoins_query = Flight::db()->prepare('
                    SELECT sb.id, sb.quantite, v.nom as ville_nom, cb.nom as categorie_nom
                    FROM bn_sinistre_besoin sb
                    JOIN bn_sinistre s ON sb.id_sinistre = s.id
                    JOIN bn_ville v ON s.id_ville = v.id
                    JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                    WHERE sb.id_categorie_besoin = ? AND sb.quantite > 0
                ');
                $besoins_query->execute([$id_categorie]);
                $besoins = $besoins_query->fetchAll();

                // Distribuer proportionnellement
                foreach ($besoins as $besoin) {
                    $quantite_a_recevoir = floor($besoin['quantite'] * $ratio);

                    if ($quantite_a_recevoir > 0) {
                        Flight::db()->prepare('
                            UPDATE bn_sinistre_besoin 
                            SET quantite = quantite - ? 
                            WHERE id = ?
                        ')->execute([$quantite_a_recevoir, $besoin['id']]);

                        $result['dons_nature_traites']++;
                        $result['details'][] = [
                            'type' => 'nature',
                            'categorie' => $besoin['categorie_nom'],
                            'quantite' => $quantite_a_recevoir,
                            'ville' => $besoin['ville_nom']
                        ];
                    }
                }

                // Réduire les dons proportionnellement
                foreach ($dons as $don) {
                    Flight::db()->prepare('
                        UPDATE bn_don_nature 
                        SET quantite = 0
                        WHERE id = ?
                    ')->execute([$don['id']]);
                }
            }

            // Distribution PROPORTIONNELLE dons argent
            $total_besoins_montant = Flight::db()->query('
                SELECT SUM(sb.quantite * sb.prix_unitaire) as total
                FROM bn_sinistre_besoin sb
                WHERE sb.quantite > 0
            ')->fetch()['total'] ?? 0;

            if ($total_besoins_montant > 0) {
                $total_dons_argent = Flight::db()->query('
                    SELECT SUM(montant_restant) as total
                    FROM bn_don_argent
                    WHERE montant_restant > 0
                ')->fetch()['total'] ?? 0;

                if ($total_dons_argent > 0) {
                    $ratio_argent = $total_dons_argent / $total_besoins_montant;

                    $besoins = Flight::db()->query('
                        SELECT sb.id, sb.quantite, sb.prix_unitaire, 
                               cb.nom as categorie_nom, v.nom as ville_nom
                        FROM bn_sinistre_besoin sb
                        JOIN bn_sinistre s ON sb.id_sinistre = s.id
                        JOIN bn_ville v ON s.id_ville = v.id
                        JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id
                        WHERE sb.quantite > 0
                    ')->fetchAll();

                    foreach ($besoins as $besoin) {
                        $montant_besoin = $besoin['quantite'] * $besoin['prix_unitaire'];
                        $montant_a_recevoir = floor($montant_besoin * $ratio_argent);
                        $quantite_satisfaite = floor($montant_a_recevoir / $besoin['prix_unitaire']);

                        if ($quantite_satisfaite > 0) {
                            Flight::db()->prepare('
                                UPDATE bn_sinistre_besoin 
                                SET quantite = quantite - ? 
                                WHERE id = ?
                            ')->execute([$quantite_satisfaite, $besoin['id']]);

                            $montant_reel = $quantite_satisfaite * $besoin['prix_unitaire'];
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

                    // Réduire les dons argent à 0
                    Flight::db()->prepare('
                        UPDATE bn_don_argent 
                        SET montant_restant = 0
                        WHERE montant_restant > 0
                    ')->execute([]);

                    $result['dons_argent_utilises'] = Flight::db()->query('
                        SELECT COUNT(*) as count
                        FROM bn_don_argent
                        WHERE montant_restant = 0 AND montant > 0
                    ')->fetch()['count'] ?? 0;
                }
            }

            $result['besoins_satisfaits'] = Flight::db()->query('
                SELECT COUNT(*) as count 
                FROM bn_sinistre_besoin 
                WHERE quantite = 0
            ')->fetch()['count'] ?? 0;

            return $result;
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
     * Simuler le dispatch sans l'appliquer (with method choice)
     * @param string $methode 'quantite' | 'proportionnalite' | 'ordre'
     * @return array Résultats du dispatch simulé
     */
    public static function simulateDispatch($methode = 'quantite')
    {
        try {
            Flight::db()->beginTransaction();
            
            if ($methode == 'ordre') {
                $result = DispatcherService::executeDispatchFIFO();
            } elseif ($methode == 'proportionnalite') {
                $result = self::executeDispatchParProportionnalite();
            } else {
                $result = self::executeDispatchParQuantite();
            }
            
            Flight::db()->rollBack(); // Annuler tous les changements
            $result['simulation'] = true;
            return $result;
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            throw $e;
        }
    }
}

