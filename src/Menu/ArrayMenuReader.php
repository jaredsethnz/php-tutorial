<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 1:01 PM
 */
namespace Forum\Menu;

class ArrayMenuReader implements MenuReader
{
    public function readMenu()
    {
        if (isset($_SESSION['loggedin']))
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