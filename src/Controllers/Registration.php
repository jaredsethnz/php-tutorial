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
use PHPMailer;

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
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        $doesntexist = 'false';
        $db = $this->commonFunctions->getDatabase();
        $sql = "SELECT * FROM User WHERE email = '$email'";
        $result = $db->query($sql);

        if ($result->size() == 0)
        {
            $doesntexist = 'true';
        }
        $this->response->setContent($doesntexist);
    }

    public function validateUsername()
    {
        $username = filter_input(INPUT_POST, 'nickname');

        $doesntexist = 'false';
        $db = $this->commonFunctions->getDatabase();
        $sql = "SELECT * FROM User WHERE nickName = '$username'";
        $result = $db->query($sql);
        if ($result->size() == 0)
        {
            $doesntexist = 'true';
        }
        $this->response->setContent($doesntexist);
    }

    public function signup()
    {
        $data = [ 'content' => "We seem to have encountered an error during signup.\n Please try again!", 'redirect' => '/registration' ];
        if ($this->request->getMethod() == 'POST' && strlen($this->request->getParameters()['extra']) == 0)
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

//            if ($this->validateEmail($params['email']) == 'true' && $this->validateUsername($params['nickname']) == 'true') {
                $sql = "INSERT INTO User VALUES('null', '$nickName', '$firstName', '$lastName', '$email', '0', '1', '$curDate', '$password', '0', '$activationHash', 'null')";
                $db = $this->commonFunctions->getDatabase();

                $emailSent = $this->emailVerification($email, $nickName, $activationHash);
                if ($emailSent) {
                    $db->execute($sql);
                    $data = ['content' => "Verification email sent to $email.\n Follow the link in the email to activate your account!", 'redirect' => '/login'];
                } else {
                    $data = ['content' => "Oops, there seems to have been an error sending a \n verification email to $email. Please try again!", 'redirect' => '/registration'];
                }
//            }
        }
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( 'refresh:5;url='.$data['redirect'] );
    }

    protected function emailVerification($email, $nickName, $activationHash)
    {
        $mailer = new PHPMailer();
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'indiecornerservices@gmail.com';
        $mailer->Password = 'Jar3dS3th';
        $mailer->SMTPSecure = 'ssl';
        $mailer->Port = 465;
        $mailer->SMTPDebug = 4;

        $mailer->setFrom('indiecornerservices@gmail.com', 'SuperSudoku');
        $mailer->addAddress($email);

        $mailer->Subject = 'Account Activation | Email Confirmation';
        $mailer->Body    = '

            Thanks '.$nickName.' for signing up!
            Your account has been created, you can login with your credentials after you have activated your account by clicking the url below.

            --------------------------------------------------------------------------------------------------------------------------------------------

            Please click this link to activate your account:
            http://localhost:8888/register/verify?email='.$email.'&hash='.$activationHash.'
            
            --------------------------------------------------------------------------------------------------------------------------------------------
            
            -IndieCornerTeam

            ';

        if(!$mailer->send()) {
           return false;
        } else {
            return true;
        }
    }

    public function accountActivation()
    {
        $data = [ 'content' => 'Invalid link!' ];
        $params = $this->request->getParameters();
        if ((isset($params['email']) && isset($params['hash'])))
        {
            $email = $params['email'];
            $hash = $params['hash'];

            $sql = "SELECT * FROM User WHERE email = '$email'";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->query($sql);
            if ($result->size() > 0)
            {
                $user = $result->fetch();
                if ($user['activationHash'] == $hash && (intval($user['activated']) == 0))
                {
                    $sql = "UPDATE User SET activated = 1 WHERE email = '$email'";
                    $db->query($sql);
                    $data = [ 'content' => "Account $email has been activated!" ];
                }
                elseif (strcmp($user['activationHash'], $hash) && (intval($user['activated']) === 1))
                {
                    $data = [ 'content' => "Account $email already activated!" ];
                }
            }
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:3;url=/login" );
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