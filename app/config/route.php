<?php

use app\controllers\DashboardController;
use app\controllers\VilleController;
use app\controllers\RegionController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DispatchController;
use app\controllers\AchatController;
use app\controllers\RecapController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$db = $app->db();
$view = $app->view();

// Page d'accueil - Redirection vers le tableau de bord
$router->get('/', function () {
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
});

// ==========================================
// Routes BNGRC - Gestion des dons sinistrés
// ==========================================

// Dashboard
$router->get('/dashboard', function () use ($db, $view) {
    $controller = new DashboardController($db, $view);
    $controller->index();
});

$router->get('/dashboard/ville/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new DashboardController($db, $view);
    $controller->detailVille($id);
});

$router->post('/dashboard/reinitialiser', function () use ($db, $view) {
    $controller = new DashboardController($db, $view);
    $controller->reinitialiser();
});

$router->post('/dashboard/mode', function () use ($db, $view) {
    $controller = new DashboardController($db, $view);
    $controller->updateMode();
});

// ==========================================
// Régions
// ==========================================
$router->get('/regions', function () use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->index();
});

$router->get('/regions/create', function () use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->create();
});

$router->post('/regions/store', function () use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->store();
});

$router->get('/regions/edit/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->edit($id);
});

$router->post('/regions/update/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->update($id);
});

$router->post('/regions/delete/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new RegionController($db, $view);
    $controller->delete($id);
});

// ==========================================
// Villes
// ==========================================
$router->get('/villes', function () use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->index();
});

$router->get('/villes/create', function () use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->create();
});

$router->post('/villes/store', function () use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->store();
});

$router->get('/villes/edit/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->edit($id);
});

$router->post('/villes/update/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->update($id);
});

$router->post('/villes/delete/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new VilleController($db, $view);
    $controller->delete($id);
});

// ==========================================
// Besoins
// ==========================================
$router->get('/besoins', function () use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->index();
});

$router->get('/besoins/create', function () use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->create();
});

$router->post('/besoins/store', function () use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->store();
});

$router->get('/besoins/edit/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->edit($id);
});

$router->post('/besoins/update/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->update($id);
});

$router->post('/besoins/delete/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new BesoinController($db, $view);
    $controller->delete($id);
});

// ==========================================
// Dons
// ==========================================
$router->get('/dons', function () use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->index();
});

$router->get('/dons/create', function () use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->create();
});

$router->post('/dons/store', function () use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->store();
});

$router->get('/dons/edit/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->edit($id);
});

$router->post('/dons/update/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->update($id);
});

$router->post('/dons/delete/@id:[0-9]+', function (int $id) use ($db, $view) {
    $controller = new DonController($db, $view);
    $controller->delete($id);
});

// ==========================================
// Dispatch
// ==========================================
$router->get('/dispatch', function () use ($db, $view) {
    $controller = new DispatchController($db, $view);
    $controller->index();
});

$router->get('/dispatch/simuler', function () use ($db, $view) {
    $controller = new DispatchController($db, $view);
    $controller->simuler();
});

$router->post('/dispatch/valider', function () use ($db, $view) {
    $controller = new DispatchController($db, $view);
    $controller->valider();
});

// ==========================================
// Achats
// ==========================================
$router->get('/achats', function () use ($db, $view) {
    $controller = new AchatController($db, $view);
    $controller->index();
});

$router->get('/achats/create', function () use ($db, $view) {
    $controller = new AchatController($db, $view);
    $controller->create();
});

$router->post('/achats/store', function () use ($db, $view) {
    $controller = new AchatController($db, $view);
    $controller->store();
});

$router->post('/achats/frais', function () use ($db, $view) {
    $controller = new AchatController($db, $view);
    $controller->updateFrais();
});

// ==========================================
// Récapitulation
// ==========================================
$router->get('/recap', function () use ($db, $view) {
    $controller = new RecapController($db, $view);
    $controller->index();
});

$router->get('/recap/api', function () use ($db, $view) {
    $controller = new RecapController($db, $view);
    $controller->apiData();
});
