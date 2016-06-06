<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 2:27 PM
 */

//const loginIndex = 0;
//const homeIndex = 1;
//
//$route;
//$routes = include ('Routes.php');
//if (!isset($_SESSION['login_user']) && $request->getPath() != '/')
//{
////    $path = $routes[0];
////    $route = $path[1][0];
//    var_dump($routes[0]);
//}
//else
//{
//    $route = $routeInfo[1][0];
//}
echo $request->getParameters()[0] == 'Jared';
return false;