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
        return [
            ['href' => '/', 'text' => 'Homepage'],
            ['href' => '/page-one', 'text' => 'Page One'],
            ['href' => '/page-two', 'text' => 'Page One'],
            ['href' => '/page-three', 'text' => 'Page One'],
        ];
    }
}