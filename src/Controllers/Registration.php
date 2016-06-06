<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 6/06/16
 * Time: 6:51 PM
 */
namespace Example\Controllers;

use Example\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;

class Registration
{
    private $request;
    private $response;
    private $renderer;

    public function __construct(
        Request $request,
        Response $response,
        FrontEndRenderer $renderer
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }
}