<?php

use app\controllers\AdminController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('index');
	});

	$router->get('/login_admin', function() use ($app) {
		$app->render('login.php');
	});

	$router->post('/dashboard', function() use ($app) {
		$adminController = new AdminController($app);
		$adminController->loginAdmin();
	});

	$router->get('/dashboard', function() use ($app) {
		$app->render('dashboard.php');
	});
	
}, [ SecurityHeadersMiddleware::class ]);