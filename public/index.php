<?php

declare(strict_types=1);

use Controllers\ErrorController;

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/MVCAutoload.php';
require_once __DIR__ . '/../src/tools.php';
require_once __DIR__ . '/../src/whoops.php';
require_once __DIR__ . '/../vendor/autoload.php';

authenticate();

$dispatcher = FastRoute\cachedDispatcher(
    function(FastRoute\RouteCollector $r) {
        // HOME
        $r->addRoute('GET', '/', 'Home');
        // LOGIN
        $r->addRoute(['GET', 'POST'], '/login', 'Login');
        $r->addRoute(['GET', 'POST'], '/signup', 'Signup');
        $r->addRoute('GET', '/logout', 'Logout');
        // IMAGES
        $r->addRoute('GET', '/image/{user:\d+}/{id:\d+}', 'GetImage');
        $r->addRoute(['GET', 'POST'], '/person', 'SelfImage');
        $r->addRoute(['GET', 'POST'], '/person/{id:\d+}', 'OtherImage');
        $r->addRoute('GET', '/best', 'BestImage');
        $r->addRoute('GET', '/latest', 'LatestImage');
    },
    [
        'cacheFile' => __DIR__ . '/../cache/route.cache',
        // After changing routes cache MUST BE FLUSHED
        'cacheDisabled' => defined('ENVIRONMENT')
                           && constant('ENVIRONMENT') == 'DEV'
    ]
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $errorController = new ErrorController(
            404, 'Requested page is not exists'
        );
        $errorController->handle([]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header('Allow: ' . implode(', ', $routeInfo[1]));
        $errorController = new ErrorController(
            405, 'Indicated method is not allow for this page'
        );
        $errorController->handle([]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $controllerType = 'Controllers\\' . $routeInfo[1] . 'Controller';
        if (!class_exists($controllerType))
        {
            $errorController = new ErrorController(
                501, 'MVC controller is not implemented for this page'
            );
            $errorController->handle([]);
            break;
        }
        $controller = new $controllerType();
        $controller->handle($routeInfo[2]);
        break;
}