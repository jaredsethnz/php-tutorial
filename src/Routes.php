<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 2/06/16
 * Time: 7:48 PM
 */

return [
    ['GET', '/login', ['Forum\Controllers\Loginpage', 'show']],
    ['POST', '/login', ['Forum\Controllers\Loginpage', 'authenticate']],
    ['GET', '/logout', ['Forum\Controllers\Loginpage', 'logout']],
    ['GET', '/registration', ['Forum\Controllers\Registration', 'show']],
    ['POST', '/registration/checkemail', ['Forum\Controllers\Registration', 'validateEmail']],
    ['POST', '/registration/checknickname', ['Forum\Controllers\Registration', 'validateUsername']],
    ['POST', '/register', ['Forum\Controllers\Registration', 'signup']],
    ['GET', '/register/verify', ['Forum\Controllers\Registration', 'accountActivation']],
    ['GET', '/', ['Forum\Controllers\Homepage', 'show']],
    ['GET', '/profile', ['Forum\Controllers\Profilepage', 'show']],
    ['POST', '/profile', ['Forum\Controllers\Profilepage', 'saveChanges']],
    ['GET', '/{slug}', ['Forum\Controllers\Page', 'show']],
];