<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 4/06/16
 * Time: 5:16 PM
 */
namespace Forum\Template;

interface Renderer
{
    public function render($template, $data = []);
}