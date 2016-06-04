<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 2/06/16
 * Time: 7:48 PM
 */

return [
    ['GET', '/', ['Example\Controllers\Homepage', 'show']],
    ['GET', '/index.php/{slug}', ['Example\Controllers\Page', 'show']],
];