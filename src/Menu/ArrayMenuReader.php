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
                ['href' => '/', 'text' => 'Home'],
                ['href' => '/forum', 'text' => 'Forum'],
                ['href' => '/challenges', 'text' => 'Challenges'],
                ['href' => '/profile', 'text' => 'Profile'],
                ['href' => '/logout', 'text' => 'Logout'],
            ];
        }
        else
        {
            return [
                ['href' => '/', 'text' => 'Home'],
                ['href' => '/forum', 'text' => 'Forum'],
                ['href' => '/challenges', 'text' => 'Challenges'],
                ['href' => '/profile', 'text' => 'Profile'],
                ['href' => '/login', 'text' => 'Login'],
            ];
        }
    }
}