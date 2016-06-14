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
use Forum\db\CommonFunctions;

class Loginpage
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
        $data = [
            'content' => '',
        ];
        $html = $this->renderer->render('Loginpage', $data);
        $this->response->setContent($html);
    }

    public function authenticate()
    {
        $data = [ 'content' => 'Invalid nickname, password combination!', 'redirect' => 'Loginpage' ];
        $params = $this->request->getParameters();

        if (isset($params['nickname']) && isset($params['password']))
        {
            $nick = $params['nickname'];
            $pass = $params['password'];
            $sql = "SELECT * FROM User WHERE nickname = '$nick'";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->query($sql);

            if ($result->size() > 0)
            {
                $result = $result->fetch();
                if (intval($result['activated']) != 0) {
                    $hash = $result['password'];
                    if (password_verify($pass, $hash) == true) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['userID'] = $result['userID'];
                        $_SESSION['nickName'] = $result['nickName'];
                        $data = ['content' => 'Welcome back ' . $_SESSION['nickName'] . '!', 'redirect' => 'Homepage'];
                    }
                }
            }
        }

        $html = $this->renderer->render($data['redirect'], $data);
        $this->response->setContent($html, true);
    }

    public function logout()
    {
        $data = [
            'content' => 'Goodbye, ' . $_SESSION['nickName'],
        ];
        session_unset();
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:2;url=/" );
    }
}