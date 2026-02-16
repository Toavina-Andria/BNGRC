<?php
namespace app\utils;

/**
 * Classe de validation des données pour empêcher les injections et valeurs invalides
 */
class Validator
{
    /**
     * Nettoyer une chaîne de caractères des caractères spéciaux dangereux
     * Autorise uniquement : lettres, chiffres, espaces, accents, ponctuation de base
     * @param string $string Chaîne à nettoyer
     * @return string Chaîne nettoyée
     */
    public static function sanitizeString($string)
    {
        if (empty($string)) {
            return '';
        }
        
        // Supprimer les balises HTML/PHP
        $string = strip_tags($string);
        
        // Convertir les caractères spéciaux en entités HTML
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        
        // Supprimer les caractères de contrôle dangereux
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);
        
        return trim($string);
    }

    /**
     * Valider qu'un montant est positif et supérieur à zéro
     * @param mixed $montant Montant à valider
     * @return bool True si valide, false sinon
     */
    public static function validatePositiveAmount($montant)
    {
        if (!is_numeric($montant)) {
            return false;
        }
        
        $montant = floatval($montant);
        return $montant > 0;
    }

    /**
     * Valider qu'une quantité est un entier positif et supérieur à zéro
     * @param mixed $quantite Quantité à valider
     * @return bool True si valide, false sinon
     */
    public static function validatePositiveInteger($quantite)
    {
        if (!is_numeric($quantite)) {
            return false;
        }
        
        $quantite = intval($quantite);
        return $quantite > 0;
    }

    /**
     * Valider qu'un ID est un entier positif
     * @param mixed $id ID à valider
     * @return bool True si valide, false sinon
     */
    public static function validateId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        
        $id = intval($id);
        return $id > 0;
    }

    /**
     * Valider une date au format Y-m-d
     * @param string $date Date à valider
     * @return bool True si valide, false sinon
     */
    public static function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Nettoyer et valider un tableau de données POST
     * @param array $data Données à nettoyer
     * @param array $stringFields Champs à nettoyer comme strings
     * @param array $numericFields Champs à valider comme nombres positifs
     * @param array $integerFields Champs à valider comme entiers positifs
     * @return array ['valid' => bool, 'data' => array, 'errors' => array]
     */
    public static function validateInput($data, $stringFields = [], $numericFields = [], $integerFields = [])
    {
        $result = [
            'valid' => true,
            'data' => [],
            'errors' => []
        ];

        // Nettoyer les champs texte
        foreach ($stringFields as $field) {
            if (isset($data[$field])) {
                $result['data'][$field] = self::sanitizeString($data[$field]);
            }
        }

        // Valider les champs numériques (décimaux)
        foreach ($numericFields as $field) {
            if (isset($data[$field])) {
                if (!self::validatePositiveAmount($data[$field])) {
                    $result['valid'] = false;
                    $result['errors'][] = "Le champ '$field' doit être un nombre positif supérieur à zéro";
                } else {
                    $result['data'][$field] = floatval($data[$field]);
                }
            }
        }

        // Valider les champs entiers
        foreach ($integerFields as $field) {
            if (isset($data[$field])) {
                if (!self::validatePositiveInteger($data[$field])) {
                    $result['valid'] = false;
                    $result['errors'][] = "Le champ '$field' doit être un entier positif supérieur à zéro";
                } else {
                    $result['data'][$field] = intval($data[$field]);
                }
            }
        }

        return $result;
    }
}
