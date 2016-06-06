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

    public function show()
    {
        $data = [];
        $html = $this->renderer->render('Registration', $data);
        $this->response->setContent($html);
    }

    public function validateEmail()
    {
        $isValid = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($isValid)
        {
            echo "<span class='status-not-available'>Email valid</span>";
        }
        else{
            echo "<span class='status-not-available'>Email not valid</span>";
        }
    }

    public function validateUsername()
    {
        $username = filter_input(INPUT_POST, 'username');
        if ($username)
        {
            echo "Username available";
        }
        else{
            echo "Username already in use";
        }
    }

    public function validatePassword()
    {
        $password = filter_input(INPUT_POST, 'password');
        if (!$password || strlen($password) < 8)
        {
            echo "Password must be at least 8 characters long";
        }
        else{
            echo "Password valid";
        }
    }

    public function signup()
    {
        $data = [];
        $html = $this->renderer->render('Homepage', $data);
        $this->response->setContent($html);
    }
}