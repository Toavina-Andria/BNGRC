<?php

use app\controllers\SinistreController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DashboardController;
use app\controllers\VilleController;
use app\controllers\InsertionController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$router->group('', function (Router $router) {

	$router->get('/', [DashboardController::class, 'index']);

	$router->group('/villes', function () use ($router) {
		// Voir les besoins d'une ville
		$router->get('/besoins', [VilleController::class, 'showVilleBesoins']);
	});

	$router->group('/besoins', function () use ($router) {
		// Liste des besoins
		$router->get('/liste', [BesoinController::class, 'getAllBesoins']);
	});

	$router->group('/dons', function () use ($router) {
		// Liste des dons
		$router->get('/liste', [DonController::class, 'listeDons']);
		// formulaire d'ajout de don
		$router->get('/insert', [DonController::class, 'showDonForm']);
		$router->post('/insert', [DonController::class, 'insertDon']);
		// dispatch des dons
		$router->get('/dispatch', [DonController::class, 'dispatchDons']);
	});

	$router->group('/sinistres', function () use ($router) {
		// Liste des sinistres
		$router->get('/liste', [SinistreController::class, 'getAllSinistres']);
		// formulaire d'ajout de sinistre (GET) et traitement (POST)
		$router->get('/insert', [InsertionController::class, 'showSinistreForm']);
		$router->post('/insert', [InsertionController::class, 'insertSinistre']);
		// routes pour besoins rattachés aux sinistres
		$router->group('/besoins', function () use ($router) {
			$router->get('/insert', [InsertionController::class, 'showBesoinForm']);
			$router->post('/insert', [InsertionController::class, 'insertBesoin']);
		});
	});

}, [SecurityHeadersMiddleware::class]);
