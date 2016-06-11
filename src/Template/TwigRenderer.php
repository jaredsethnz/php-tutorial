<?php

/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 12:28 PM
 */
namespace Forum\Template;
use Forum\Template\Renderer;
use Twig_Environment;

class TwigRenderer implements Renderer
{
    private $renderer;

    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render($template, $data = [])
    {
        return $this->renderer->render("$template.html", $data);
    }
}