<?php

namespace Example;

use Http\Request;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';

/**
 * Register the error handler
 */
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Friendly error page and send an email to the developer';
    });
}
$whoops->register();

$injector = include('Dependencies.php');

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

//  CHECK IF A USER IS LOGGED IN
session_start();
if (!isset($_SESSION['logged_in']) && !nonLoggedInRoutes($request))
{
    $className = 'Forum\Controllers\Loginpage';
    $method = 'show';
    $class = $injector->make($className);
    $class->$method();
}
else
{
    switch ($routeInfo[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
            $response->setContent('404 - Page not found');
            $response->setStatusCode(404);
            break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->setContent('405 - Method not allowed');
            $response->setStatusCode(405);
            break;
        case \FastRoute\Dispatcher::FOUND:
            //var_dump(include ('LoginAuthenticator.php'));
            $className = $routeInfo[1][0];
            $method = $routeInfo[1][1];
            $vars = $routeInfo[2];

            $class = $injector->make($className);
            $class->$method($vars);
            break;
    }
    foreach ($response->getHeaders() as $header) {
        header($header, false);
    }
}
echo $response->getContent();

function nonLoggedInRoutes(Request $request)
{
    switch ($request->getPath()) {
        case '/':
            return true;
        case '/registration':
            return true;
        case '/register':
            return true;
        case '/registration/checkemail':
            return true;
        case '/registration/checkemail':
            return true;
        case '/registration/checkpass':
            return true;
        case  '/registration/checkusername':
            return true;
        default:
            return false;
    }
}