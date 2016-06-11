<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 3/06/16
 * Time: 6:28 PM
 */
$injector = new \Auryn\Injector;


// HtTTP Response and Requests
$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

// Twiggy stuffs
$injector->alias('Forum\Template\Renderer', 'Forum\Template\TwigRenderer');
$injector->alias('Forum\Template\FrontendRenderer', 'Forum\Template\FrontendTwigRenderer');

$injector->delegate('Twig_Environment', function() use ($injector) {
    $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
    $twig = new Twig_Environment($loader);
    return $twig;
});

 // Page file reader
$injector->define('Forum\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('Forum\Page\PageReader', 'Forum\Page\FilePageReader');
$injector->share('Forum\Page\FilePageReader');

$injector->alias('Forum\Menu\MenuReader', 'Forum\Menu\ArrayMenuReader');
$injector->share('Forum\Menu\ArrayMenuReader');

return $injector;