<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 6/06/16
 * Time: 6:51 PM
 */
namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
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
            $html = "<span class='status-not-available'>Email valid</span>";
            $this->response->setContent($html);
        }
        else{
            $html = "<span class='status-not-available'>Email not valid</span>";
            $this->response->setContent($html);
        }
    }

    public function validateUsername()
    {
        $username = filter_input(INPUT_POST, 'username');
        if ($username)
        {
            $html = "Username available";
            $this->response->setContent($html);
        }
        else{
            $html = "Username already in use";
            $this->response->setContent($html);
        }
    }

    public function validatePassword()
    {
        $password = filter_input(INPUT_POST, 'password');
        if (!$password || strlen($password) < 8)
        {
            $html = "Password must be at least 8 characters long";
            $this->response->setContent($html);
        }
        else{
            $html = "Password valid";
            $this->response->setContent($html);
        }
    }

    public function signup()
    {
        $data = [];
        $html = $this->renderer->render('Homepage', $data);
        $this->response->setContent($html);
    }
}