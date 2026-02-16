<?php

use app\controllers\SinistreController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DashboardController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$router->group('', function(Router $router)  {

	$router->get('/', [DashboardController::class, 'index']);

	$router->group('/besoins', function() use ($router) {
		// Liste des besoins
			$router->get('/liste', [BesoinController::class, 'getAllBesoins']);
	});

	$router->group('/sinistres', function() use ($router) {
				// Liste des sinistres
		$router->get('/liste', [SinistreController::class, 'getAllSinistres']);
		// formulaire d'ajout de sinistre (GET) et traitement (POST)
		$router->get('/insert', [\app\controllers\InsertionController::class, 'showSinistreForm']);
		$router->post('/insert', [\app\controllers\InsertionController::class, 'insertSinistre']);
			// routes pour besoins rattachés aux sinistres
		$router->group('/besoins', function() use ($router) {
				$router->get('/insert', [\app\controllers\InsertionController::class, 'showBesoinForm']);
				$router->post('/insert', [\app\controllers\InsertionController::class, 'insertBesoin']);
		});
	});

}, [ SecurityHeadersMiddleware::class ]);
