<?php

/**
 * A bootstrap which is responsible for loading all required files and classes
 * in order to setup the application.
 *
 * @author  Joeri Hermans
 * @since   16 February 2016
 */

// Load required functions and scripts.
require_once "core/inc.functions.php";
// Register the class autoloader function.
spl_autoload_register('loadClass');

use \Carbon\Application\Application;
use \Work\Application\WorkApplication;

// Allocate the desired application and applicationEvent.
$app = new WorkApplication();
Application::setInstance($app);
parseConfiguration("Database");
parseConfiguration("Main");

// Load required device check file
require_once "core/inc.device.php";

// Load required language file
require_once "core/inc.language.php";

use \Carbon\Router\Router;

// Allocate a router, and add initial basic pages.
$router = new Router();
$app->setRouter($router);
$router->setBase($app->getConfiguration("base"));
$router->registerPage(\Carbon\Page\PageNotFound::PATH, "\Carbon\Page\PageNotFound");
// Register the application pages.
registerPages($router);
// Route the user to the desired page.
$router->route();
$view = $router->getView();
$view->draw();
