<?php
use app\controllers\AdminController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\AchatController;
use app\controllers\BesoinVilleController;
use app\controllers\SimulationController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\VilleController;
/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function (Router $router) use ($app) {

	$router->get('/', function () use ($app) {
		$app->render('index');
	});

	$router->get('/login_admin', function () use ($app) {
		$app->render('login.php');
	});

	$router->post('/login_admin', function () use ($app) {
		$adminController = new AdminController($app);
		$adminController->loginAdmin();
	});

	$router->get('/dashboard', function () use ($app) {
		// if(!isset($_SESSION['admin'])) {
		// 	$app->redirect('/login_admin');
		// 	return;
		// }
		$besoinController = new BesoinController($app);
		$besoinController->dashboardWithTotal();
	});

	$router->get('/villes-impactees', function () use ($app) {
		$villeController = new VilleController($app);
		$villeController->villesImpactees();
	});

	$router->get('/gestion-dons', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$donController = new DonController($app);
		$donController->gestionDons();
	});

	$router->post('/ajouter-don', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$donController = new DonController($app);
		$donController->ajouterDon();
	});

	$router->get('/supprimer-don/@id', function ($id) use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$donController = new DonController($app);
		$donController->supprimerDon($id);
	});

	/*
	$router->get('/detail-ville-besoins', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$besoinVilleController = new BesoinVilleController($app);
		$besoinVilleController->detailVilleBesoins();
	});
	*/

	$router->post('/ajouter-besoin-ville', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$besoinVilleController = new BesoinVilleController($app);
		$besoinVilleController->ajouterBesoinVille();
	});

	$router->get('/supprimer-besoin-ville/@id', function ($id) use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$besoinVilleController = new BesoinVilleController($app);
		$besoinVilleController->supprimerBesoinVille($id);
	});

	$router->get('/achats', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$achatController = new AchatController($app);
		$achatController->pageAchats();
	});

	$router->post('/update-frais', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$achatController = new AchatController($app);
		$achatController->updateFrais();
	});

	$router->post('/effectuer-achat', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$achatController = new AchatController($app);
		$achatController->effectuerAchat();
	});

	$router->get('/simulation', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$simulationController = new SimulationController($app);
		$simulationController->index();
	});

	$router->post('/simulation/simuler', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$simulationController = new SimulationController($app);
		$simulationController->simuler();
	});

	$router->post('/simulation/valider', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$simulationController = new SimulationController($app);
		$simulationController->valider();
	});

	$router->get('/simulation/reinitialiser', function () use ($app) {
		if (!isset($_SESSION['admin'])) {
			$app->redirect('/login_admin');
			return;
		}
		$simulationController = new SimulationController($app);
		$simulationController->reinitialiser();
	});

}, [SecurityHeadersMiddleware::class]);
?>