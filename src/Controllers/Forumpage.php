<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 13/06/16
 * Time: 9:46 PM
 */

namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;
use Forum\db\CommonFunctions;

class Forumpage
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
        $data = [ 'content' => 'THIS IS THE FORUM!!!' ];
        $nick = $_SESSION['nickName'];
        $userID = $_SESSION['userID'];
        $sql = "SELECT * FROM User WHERE nickName = '$nick' AND userID = '$userID'";
        $db = $this->commonFunctions->getDatabase();
        //$result = $db->query($sql);
//        if ($result->size() > 0) {
//            $result = $result->fetch();
//            $data = [
//                'nickname' => $result['nickName'],
//                'firstname' => $result['firstName'],
//                'lastname' => $result['lastName'],
//                'email' => $result['email'],
//                'challengeable' => intval($result['challengeable']),
//                'rank' => intval($result['rank']),
//                'profilepic' => $result['profilePic'] == 'null' ? 'images/profileImages/default.jpg' : ($result['profilePic'])
//            ];
//        }

        $html = $this->renderer->render('Forumpage', $data);
        $this->response->setContent($html);
    }
}