<?php
namespace app\controllers;
use app\models\BesoinModel;
use flight\Engine;
use Flight;

Class BesoinController{
    protected Engine $app;

    public function __construct($app){
        $this->app = $app;
    }

	public function getAllBesoins(): void {

		$this->app->render('besoin/liste',[
				'id' => 1,
				'ville' => 'Toamasina',
				'region' => 'Antsinanana',
				'categorie' => 'nature',
				'article' => 'Riz',
				'description' => 'Besoin urgent en riz pour les sinistrés',
				'quantite' => 500,
				'unite' => 'kg',
				'quantite_restante' => 350,
				'statut' => 'partiellement_satisfait',
				'date_besoin' => '2024-01-15 10:30:00'
		]);

		$this->app->json($besoins, 200, true, 'utf-8', JSON_PRETTY_PRINT);
	}

	public function getBesoin($id) {
		$besoins = [
			1 => [
				'id' => 1,
				'ville' => 'Toamasina',
				'region' => 'Antsinanana',
				'categorie' => 'nature',
				'article' => 'Riz',
				'description' => 'Besoin urgent en riz pour les sinistrés',
				'quantite' => 500,
				'unite' => 'kg',
				'quantite_restante' => 350,
				'statut' => 'partiellement_satisfait',
				'date_besoin' => '2024-01-15 10:30:00'
			]
		];

		if(isset($besoins[$id])) {
			$this->app->json($besoins[$id], 200, true, 'utf-8', JSON_PRETTY_PRINT);
		} else {
			$this->app->json(['error' => 'Besoin non trouvé'], 404, true, 'utf-8', JSON_PRETTY_PRINT);
		}
	}

	public function getBesoinsByStatut($statut) {
		$besoins = [
			[
				'id' => 1,
				'ville' => 'Toamasina',
				'region' => 'Antsinanana',
				'categorie' => 'nature',
				'article' => 'Riz',
				'description' => 'Besoin urgent en riz pour les sinistrés',
				'quantite' => 500,
				'unite' => 'kg',
				'quantite_restante' => 350,
				'statut' => 'partiellement_satisfait',
				'date_besoin' => '2024-01-15 10:30:00'
			]
		];

		$besoins_filtres = array_filter($besoins, function($besoin) use ($statut) {
			return $besoin['statut'] === $statut;
		});

		$this->app->json(array_values($besoins_filtres), 200, true, 'utf-8', JSON_PRETTY_PRINT);
	}

	public function getBesoinsByVille($ville) {
		$besoins = [
			[
				'id' => 1,
				'ville' => 'Toamasina',
				'region' => 'Antsinanana',
				'categorie' => 'nature',
				'article' => 'Riz',
				'description' => 'Besoin urgent en riz pour les sinistrés',
				'quantite' => 500,
				'unite' => 'kg',
				'quantite_restante' => 350,
				'statut' => 'partiellement_satisfait',
				'date_besoin' => '2024-01-15 10:30:00'
			]
		];

		$besoins_filtres = array_filter($besoins, function($besoin) use ($ville) {
			return strtolower($besoin['ville']) === strtolower($ville);
		});

		$this->app->json(array_values($besoins_filtres), 200, true, 'utf-8', JSON_PRETTY_PRINT);
	}


	public function getDashboardStats() {
		$stats = [
			'total_besoins' => 7,
			'total_quantite' => 2450,
			'villes_concernees' => 5,
			'repartition_par_statut' => [
				'en_attente' => 2,
				'partiellement_satisfait' => 4,
				'satisfait' => 1
			],
			'repartition_par_categorie' => [
				'nature' => 4,
				'materiaux' => 2,
				'argent' => 1
			]
		];

		$this->app->json($stats, 200, true, 'utf-8', JSON_PRETTY_PRINT);
	}
}