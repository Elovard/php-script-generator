<?php
    namespace Core;

    include ('project/utils/generator.php');
    include('vendor/autoload.php');

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    spl_autoload_register();

    $routes = require $_SERVER['DOCUMENT_ROOT'] . '/project/config/routes.php';

    $router = new Router();
    $track = $router->getTrack($routes, $_SERVER['REQUEST_URI']);

    $dispatcher = new Dispatcher();
    $page = $dispatcher->getPage($track);

    echo (new View) -> render($page);
?>
