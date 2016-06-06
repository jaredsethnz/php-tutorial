<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 1:01 PM
 */
namespace Example\Menu;

class ArrayMenuReader implements MenuReader
{
    public function readMenu()
    {
        if (isset($_SESSION['logged_in']))
        {
            return [
                ['href' => '/', 'text' => 'Homepage'],
                ['href' => '/page-one', 'text' => 'Page One'],
                ['href' => '/page-two', 'text' => 'Page Two'],
                ['href' => '/page-three', 'text' => 'Page Three'],
                ['href' => '/logout', 'text' => 'Logout'],
            ];
        }
        else
        {
            return [
                ['href' => '/', 'text' => 'Homepage'],
                ['href' => '/page-one', 'text' => 'Page One'],
                ['href' => '/page-two', 'text' => 'Page Two'],
                ['href' => '/page-three', 'text' => 'Page Three'],
                ['href' => '/login', 'text' => 'Login'],
            ];
        }
    }
}