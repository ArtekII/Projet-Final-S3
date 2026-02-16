<?php

$ds = DIRECTORY_SEPARATOR;

// Autoload ONCE
require(__DIR__ . $ds . '..' . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php');

use Flight;
use PDO;

session_start();

// Config check
if (file_exists(__DIR__ . $ds . 'config.php') === false) {
    Flight::halt(
        500,
        'Config file not found. Please create a config.php file in the app/config directory to get started.'
    );
}

// Attach static Flight calls to the app instance
$app = Flight::app();

// Load config
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
// define('BASE_URL', '/ETU004248/ETU004248-ETU003886/');
$config = require 'config.php';
// Load services (OTHER services)
require 'services.php';


// Router
$router = $app->router();

// Routes (safe now)
require 'route.php';

// Start app
$app->start();
