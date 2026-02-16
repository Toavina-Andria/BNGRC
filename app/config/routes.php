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

$router->group('', function(Router $router) use ($app) {

	$router->group('/besoins', function() use ($router, $app) {

		// Liste des besoins
		$router->get('/', function() use ($app) {
			$besoinController = new BesoinController($app);
			$besoins = $besoinController->getAllBesoins();
			$app->render('besoin/liste', [ 'besoins' => $besoins ]);
		});

	});

}, [ SecurityHeadersMiddleware::class ]);