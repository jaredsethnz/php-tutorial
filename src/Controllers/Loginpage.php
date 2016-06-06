<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 2:02 PM
 */
namespace Example\Controllers;

use Example\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;

class Loginpage
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

    public function show()
    {
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render('Login', $data);
        $this->response->setContent($html);
    }

    public function authenticate()
    {
        echo "TESTING";
        var_dump($this->request->getParameters());
    }
}