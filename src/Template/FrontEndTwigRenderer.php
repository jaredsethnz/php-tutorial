<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 12:48 PM
 */
namespace Forum\Template;

use Forum\Menu\MenuReader;

class FrontEndTwigRenderer implements FrontEndRenderer
{
    private $renderer;
    private $menuReader;

    public function __construct(Renderer $renderer, MenuReader $menuReader)
    {
        $this->renderer = $renderer;
        $this->menuReader = $menuReader;
    }

    public function render($template, $data = [])
    {
        $scriptPath = '../js/';
        $cssPath = '../css/';
        $data = array_merge($data, [
            'menuItems' => $this->menuReader->readMenu(),
            'scriptPath' => $scriptPath,
            'cssPath' =>$cssPath,
        ]);
        return $this->renderer->render($template, $data);
    }
}