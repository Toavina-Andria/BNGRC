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
		$router->get('/', function()  {

		});

	});

}, [ SecurityHeadersMiddleware::class ]);
