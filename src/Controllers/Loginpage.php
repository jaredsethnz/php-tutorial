<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 5/06/16
 * Time: 2:02 PM
 */
namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
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
        $params = $this->request->getParameters();
        if (isset($params['username']) && isset($params['password']))
        {
            if (strlen($params['username']) > 0 && strlen($params['password']) > 0) {
                $this->authenticate();
            }
            else
            {
                $this->authenticateFailure();
            }
        }
        else
        {
            $data = [
                'status' => '',
            ];
            $html = $this->renderer->render('Loginpage', $data);
            $this->response->setContent($html);
        }
    }

    public function authenticate()
    {
        $params = $this->request->getParameters();
        if ($params['username'] == 'Jared')
        {
            //echo $this->request->getPath();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $params['username'];
            $data = [
                'name' => $_SESSION['username'],
            ];
            $html = $this->renderer->render('Homepage', $data);
            $this->response->setContent($html);
        }
        else
        {
            $this->authenticateFailure();
        }
    }

    public function authenticateFailure()
    {
        $data = [
            'status' => 'Incorrect username or password combination.',
        ];
        $html = $this->renderer->render('Loginpage', $data);
        $this->response->setContent($html);
    }

    public function logout()
    {
        $data = [
            'content' => 'Goodbye, ' . $_SESSION['username'],
        ];
        session_unset();
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
    }
}