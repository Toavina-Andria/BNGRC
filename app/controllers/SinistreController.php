<?php
namespace app\controllers;

use app\models\Sinistre;
use flight\Engine;
use Flight;
use Throwable;

Class SinistreController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }

public function getAllSinistres() {

    $sinistres = [
        [
            'id' => 1,
            'nombre_sinistres' => 150,
            'ville' => 'Toamasina',
            'region' => 'Atsinanana',
            'date_sinistre' => '2024-01-10',
            'description' => 'Inondations dans le district'
        ]
    ];

    $this->app->render('sinistre/liste', [
        'sinistres' => $sinistres,
        'basepath' => $this->app->get('base_path')
    ]);
}


    public function getSinistre($id) {
        $sinistres = [
            1 => [
                'id' => 1,
                'nombre_sinistres' => 150,
                'ville' => 'Toamasina',
                'region' => 'Atsinanana',
                'date_sinistre' => '2024-01-10',
                'description' => 'Inondations dans le district'
            ]
        ];

        if(isset($sinistres[$id])) {
            $this->app->json($sinistres[$id], 200, true, 'utf-8', JSON_PRETTY_PRINT);
        } else {
            $this->app->json(['error' => 'Sinistre non trouvé'], 404, true, 'utf-8', JSON_PRETTY_PRINT);
        }
    }

    public function getSinistresByRegion($region) {
        $sinistres = [
            [
                'id' => 1,
                'nombre_sinistres' => 150,
                'ville' => 'Toamasina',
                'region' => 'Atsinanana',
                'date_sinistre' => '2024-01-10',
                'description' => 'Inondations dans le district'
            ]
        ];

        $sinistres_filtres = array_filter($sinistres, function($sinistre) use ($region) {
            return strtolower($sinistre['region']) === strtolower($region);
        });

        $this->app->json(array_values($sinistres_filtres), 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }

    public function getSinistresByVille($ville) {
        $sinistres = [
            [
                'id' => 1,
                'nombre_sinistres' => 150,
                'ville' => 'Toamasina',
                'region' => 'Atsinanana',
                'date_sinistre' => '2024-01-10',
                'description' => 'Inondations dans le district'
            ]
        ];

        $sinistres_filtres = array_filter($sinistres, function($sinistre) use ($ville) {
            return strtolower($sinistre['ville']) === strtolower($ville);
        });

        $this->app->json(array_values($sinistres_filtres), 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }

    public function getSinistreStats() {
        $stats = [
            'total_sinistres' => 5,
            'total_personnes_impactees' => 630,
            'regions_concernees' => 5,
            'moyenne_par_sinistre' => 126,
            'repartition_par_region' => [
                'Atsinanana' => 150,
                'Haute Matsiatra' => 85,
                'Boeny' => 200,
                'Atsimo-Andrefana' => 75,
                'Diana' => 120
            ],
            'sinistre_plus_important' => [
                'region' => 'Boeny',
                'ville' => 'Mahajanga',
                'nombre_sinistres' => 200,
                'type' => 'Cyclone'
            ],
            'evolution_par_mois' => [
                'Janvier' => 630,
                'Fevrier' => 0,
                'Mars' => 0
            ]
        ];

        $this->app->json($stats, 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }

    /**
     * Récupère les besoins associés à un sinistre
     */
    public function getSinistreBesoins($id) {
        $besoins_par_sinistre = [
            1 => [
                [
                    'id' => 1,
                    'categorie' => 'nature',
                    'article' => 'Riz',
                    'quantite' => 500,
                    'unite' => 'kg',
                    'priorite' => 'haute'
                ],
                [
                    'id' => 2,
                    'categorie' => 'nature',
                    'article' => 'Huile',
                    'quantite' => 200,
                    'unite' => 'litres',
                    'priorite' => 'moyenne'
                ]
            ],
            2 => [
                [
                    'id' => 3,
                    'categorie' => 'materiaux',
                    'article' => 'Tôles',
                    'quantite' => 100,
                    'unite' => 'pieces',
                    'priorite' => 'haute'
                ]
            ],
            3 => [
                [
                    'id' => 4,
                    'categorie' => 'nature',
                    'article' => 'Eau',
                    'quantite' => 1000,
                    'unite' => 'litres',
                    'priorite' => 'critique'
                ],
                [
                    'id' => 5,
                    'categorie' => 'materiaux',
                    'article' => 'Tentes',
                    'quantite' => 50,
                    'unite' => 'pieces',
                    'priorite' => 'haute'
                ],
                [
                    'id' => 6,
                    'categorie' => 'argent',
                    'article' => null,
                    'quantite' => 5000000,
                    'unite' => 'FCFA',
                    'priorite' => 'moyenne'
                ]
            ]
        ];

        if(isset($besoins_par_sinistre[$id])) {
            $this->app->json($besoins_par_sinistre[$id], 200, true, 'utf-8', JSON_PRETTY_PRINT);
        } else {
            $this->app->json(['message' => 'Aucun besoin trouvé pour ce sinistre'], 200, true, 'utf-8', JSON_PRETTY_PRINT);
        }
    }

    /**
     * Récupère le résumé des sinistres pour le dashboard
     */
    // affiche le formulaire d'ajout de sinistre
    public function showInsertForm() {
        // Get villes for dropdown
        $villes = [];
        // TODO: Fetch from database using Ville model
        
        $this->app->render('sinistre/form', [
            'villes' => $villes,
            'basepath' => $this->app->get('base_path')
        ]);
    }

    // traite l'envoi du formulaire d'ajout
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

    public function getSinistreResume() {
        $resume = [
            'actifs' => 4,
            'resolus' => 1,
            'urgence_immediate' => 2,
            'zones_critiques' => [
                'Mahajanga' => 'Cyclone - 200 personnes',
                'Toamasina' => 'Inondations - 150 personnes'
            ],
            'derniers_sinistres' => [
                [
                    'ville' => 'Antsiranana',
                    'date' => '2024-01-20',
                    'impact' => 120
                ],
                [
                    'ville' => 'Toliara',
                    'date' => '2024-01-18',
                    'impact' => 75
                ],
                [
                    'ville' => 'Mahajanga',
                    'date' => '2024-01-15',
                    'impact' => 200
                ]
            ]
        ];

        $this->app->json($resume, 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }
}
