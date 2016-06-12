<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 2/06/16
 * Time: 9:59 PM
 */
namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;
use Forum\db\CommonFunctions;

class Homepage
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
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->commonFunctions = $cf;
    }

    public function show()
    {
        $db = $this->commonFunctions->getDatabase();
        $message = '';
        if (isset($_SESSION['nickName']))
        {
            $message = 'Welcome back, '.$_SESSION['nickName'].'!';
        }
        else
        {
            $message = 'Discuss - Challenge - Play';
        }
        $data = [
            'content' => $message,
        ];
        $html = $this->renderer->render('Homepage', $data);
        $this->response->setContent($html);
    }
}