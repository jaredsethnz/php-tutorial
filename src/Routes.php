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
    ['GET', '/registration', ['Example\Controllers\Registration', 'show']],
    ['POST', '/registration/checkemail', ['Example\Controllers\Registration', 'validateEmail']],
    ['POST', '/registration/checkpass', ['Example\Controllers\Registration', 'validatePassword']],
    ['POST', '/registration/checkusername', ['Example\Controllers\Registration', 'validateUsername']],
    ['POST', '/register', ['Example\Controllers\Registration', 'signup']],
    ['GET', '/', ['Example\Controllers\Homepage', 'show']],
    ['GET', '/{slug}', ['Example\Controllers\Page', 'show']],
];