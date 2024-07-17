<?php
    session_start();

    require __DIR__ . '/../vendor/autoload.php';

    require '../helpers.php';

    use Framework\Router;


    // Instatiating the Router 
    $router = new Router();

    // Get Routes
    $routes = require basePath('routes.php');

    // Get URI and HTTP METHOD
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Route the Request
    $router->route($uri);
