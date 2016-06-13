<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 13/06/16
 * Time: 9:47 PM
 */

namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;
use Forum\db\CommonFunctions;

class Challengepage
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
        $nick = $_SESSION['nickName'];
        $userID = $_SESSION['userID'];
        $sql = "SELECT * FROM User";
        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sql);
        $result = $result->fetchResult();
        $users = [];
        $count = 0;
        while ($row = $result->fetch_assoc())
        {
            $data['user'.$count] = $row;
            $count++;
        }
//        var_dump($data);
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

        $html = $this->renderer->render('Challengepage', array('users' => $data));
        $this->response->setContent($html);
    }
}