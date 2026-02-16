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
/**
 * @var Router $router
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function (Router $router) {
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function () {
		// Render the home page view (views/home.php)
	});

	$router->get('/hello-world/@name', function ($name) {
		echo '<h1>Hello world! Oh hey ' . $name . '!</h1>';
	});

	$router->group('/api', function () use ($router) {
		$router->get('/users', [ApiExampleController::class, 'getUsers']);
		$router->get('/users/@id:[0-9]', [ApiExampleController::class, 'getUser']);
		$router->post('/users/@id:[0-9]', [ApiExampleController::class, 'updateUser']);
	$router->group('/besoins', function() use ($router, $app) {

		// Liste des besoins
		$router->get('/', function() use ($app) {
			$besoinController = new BesoinController($app);
			$besoins = $besoinController->getAllBesoins();
			$app->render('besoin/liste', [ 'besoins' => $besoins ]);
		});

	});

}, [SecurityHeadersMiddleware::class]);