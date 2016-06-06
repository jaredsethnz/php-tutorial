<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 2/06/16
 * Time: 7:48 PM
 */

return [
    ['GET', '/login', ['Example\Controllers\Loginpage', 'show']],
    ['GET', '/loginauth', ['Example\Controllers\Loginpage', 'authenticate']],
    ['GET', '/logout', ['Example\Controllers\Loginpage', 'logout']],
    ['GET', '/', ['Example\Controllers\Homepage', 'show']],
    ['GET', '/{slug}', ['Example\Controllers\Page', 'show']],
];