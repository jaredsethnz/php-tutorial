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

// Mustachey stuffs
//$injector->define('Mustache_Engine', [
//    ':options' => [
//        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
//            'extension' => '.html',
//        ]),
//    ],
//]);

// Twiggy stuffs
$injector->alias('Example\Template\Renderer', 'Example\Template\TwigRenderer');
$injector->alias('Example\Template\FrontendRenderer', 'Example\Template\FrontendTwigRenderer');

$injector->delegate('Twig_Environment', function() use ($injector) {
    $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
    $twig = new Twig_Environment($loader);
    return $twig;
});

 // Page file reader
$injector->define('Example\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('Example\Page\PageReader', 'Example\Page\FilePageReader');
$injector->share('Example\Page\FilePageReader');

$injector->alias('Example\Menu\MenuReader', 'Example\Menu\ArrayMenuReader');
$injector->share('Example\Menu\ArrayMenuReader');

return $injector;