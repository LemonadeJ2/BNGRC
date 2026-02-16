<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/dashboard', function() use ($app) {
		$app->render('dashboard');
	});

	$router->get('/dashboard', function() use ($app) {
		$app->render('dashboard.php');
	});
	
}, [ SecurityHeadersMiddleware::class ]);