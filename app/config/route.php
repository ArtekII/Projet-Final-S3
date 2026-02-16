<?php

use app\controllers\AdminController;
use app\controllers\UserController;
use app\controllers\CategoryController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$db = $app->db();
$view = $app->view();

$adminController = new AdminController($db, $view);
$userController = new UserController($db, $view);

$router->get('/', function () use ($view) {
    echo $view->render('home', ['pageTitle' => 'Accueil']);
});

$router->get('/logout', function () {
    unset($_SESSION['user'], $_SESSION['admin']);
    session_regenerate_id(true);
    header('Location: ' . BASE_URL . '/login');
    exit;
});

/* ADMIN */
$router->get('/admin', [$adminController, 'showLogin']);
$router->post('/admin/login', [$adminController, 'login']);
$router->get('/admin/logout', [$adminController, 'logout']);
$router->get('/admin/dashboard', [$adminController, 'dashboard']);
$router->get('/admin/stats', [$adminController, 'dashboard']);

/* USER */
$router->get('/register', [$userController, 'showRegister']);
$router->post('/register', [$userController, 'register']);
$router->get('/login', [$userController, 'showLogin']);
$router->post('/login', [$userController, 'login']);


$router->group('', function (Router $router) use ($app) {

    // ==========================================
    // Routes pour la gestion des catégories (Admin)
    // ==========================================
    $router->group('/admin/categories', function (Router $router) use ($app) {
        
        // Liste des catégories
        $router->get('', function () use ($app) {
            $controller = new CategoryController($app);
            $controller->index();
        });

        // Formulaire de création
        $router->get('/create', function () use ($app) {
            $controller = new CategoryController($app);
            $controller->create();
        });

        // Enregistrer une nouvelle catégorie
        $router->post('/store', function () use ($app) {
            $controller = new CategoryController($app);
            $controller->store();
        });

        // Formulaire de modification
        $router->get('/edit/@id:[0-9]+', function (int $id) use ($app) {
            $controller = new CategoryController($app);
            $controller->edit($id);
        });

        // Mettre à jour une catégorie
        $router->post('/update/@id:[0-9]+', function (int $id) use ($app) {
            $controller = new CategoryController($app);
            $controller->update($id);
        });

        // Supprimer une catégorie
        $router->post('/delete/@id:[0-9]+', function (int $id) use ($app) {
            $controller = new CategoryController($app);
            $controller->delete($id);
        });
    });



}, [SecurityHeadersMiddleware::class]);
