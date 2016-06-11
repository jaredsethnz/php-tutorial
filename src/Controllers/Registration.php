<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 6/06/16
 * Time: 6:51 PM
 */
namespace Forum\Controllers;

use Forum\db\CommonFunctions;
use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;

class Registration
{
    private $request;
    private $response;
    private $renderer;
    private $commonFunctions;

    public function __construct(
        Request $request,
        Response $response,
        FrontEndRenderer $renderer,
        CommonFunctions $cf
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->commonFunctions = $cf;
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
        if ($this->request->getMethod() == 'POST')
        {
            $params = $this->request->getParameters();
            $firstName = $this->sanitize($params['firstName']);
            $lastName = $this->sanitize($params['lastName']);
            $email = $this->sanitize($params['email']);
            $nickName = $this->sanitize($params['nickname']);
            $password = $this->sanitize($params['password']);
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $activationHash = password_hash($this->generateRandomString(), PASSWORD_DEFAULT, ['cost' => 12]);
            $curDate = date('Y-d-m');


            $sql = "INSERT INTO User
                    VALUES('$nickName', '$firstName', '$lastName', '$email', '0', '1', '$curDate', '$password', '0', '$activationHash', 'null')";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->query($sql);

            $this->emailVerification($email, $activationHash);
            $this->response->setContent(var_dump($result));
            //$this->response->setContent("$firstName, $lastName, $email, $nickName, $password");

        }
       // $html = $this->renderer->render('Homepage', $data);
        //$this->response->setContent($html);
    }

    protected function emailVerification($email, $activationHash)
    {
        $to = $email; // Send email to our user
        echo $to;
        $subject = 'Signup | Verification'; // Give the email a subject
        $message = '
 
            Thanks for signing up!
            Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
            
            --------------------------------------------------------------------------------------------------------------------------------------------
             
            Please click this link to activate your account:
            http://localhost:8888/verify.php?email='.$email.'&hash='.$activationHash.'
             
            '; // Our message above including the link

        $headers = 'From:noreply@supersudoku.com' . "\r\n"; // Set from headers
        $headers .= "Reply-To:noreply@supersudoku.com . \r\n";
        $mailSent = mail($to, $subject, $message, $headers); // Send our email

        if ($mailSent)
        {
            echo "SENT";
        }
        else
        {
            echo "NOT SENT";
        }
    }

    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function sanitize($param)
    {
        return htmlentities($param, ENT_QUOTES, 'UTF-8');
    }
}