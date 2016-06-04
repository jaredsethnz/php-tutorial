<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 4/06/16
 * Time: 5:18 PM
 */
namespace Example\Template;

use Mustache_Engine;

class MustacheRenderer implements Renderer
{
    private $engine;

    public function __construct(Mustache_Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render($template, $data = [])
    {
        return $this->engine->render($template, $data);
    }
}