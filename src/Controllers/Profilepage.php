<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 13/06/16
 * Time: 12:55 PM
 */

namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;
use Forum\db\CommonFunctions;
use Eventviva\ImageResize;

class Profilepage
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
        $sql = "SELECT * FROM User WHERE nickName = '$nick' AND userID = '$userID'";
        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sql);
        if ($result->size() > 0) {
            $result = $result->fetch();
            $data = [
                'nickname' => $result['nickName'],
                'firstname' => $result['firstName'],
                'lastname' => $result['lastName'],
                'email' => $result['email'],
                'challengeable' => intval($result['challengeable']),
                'rank' => intval($result['rank']),
                'profilepic' => empty($result['profilePic']) ? 'images/profileImages/default.jpg' : ($result['profilePic'])
            ];
        }

        $html = $this->renderer->render('Profilepage', $data);
        $this->response->setContent($html);
    }

    public function saveChanges()
    {
        $data = [ 'content' => 'Error saving changes!' ];
        $method = $this->request->getMethod();

        if($method == 'POST') {
            $uploadDir = 'images/profileImages/';
            $fileName = $_FILES['photo']['name'];
            $tmpName  = $_FILES['photo']['tmp_name'];
            $extension = explode('.', $fileName);
            $extension = $extension[count($extension)-1];

            $filePath = $uploadDir . $_SESSION['nickName'] . 'ProfilePic.' . $extension;

            $result = move_uploaded_file($tmpName, $filePath);
            if (!$result) {
                echo "Error uploading file";
                exit;
            }
            $image = new ImageResize($filePath);
            $image->resizeToBestFit(150, 150, true);
            $image->save($filePath);

            $nick = $_SESSION['nickName'];
            $sql = "UPDATE user SET profilePic = '$filePath' WHERE nickName = '$nick'";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->execute($sql);
            if ($result)
            {
                $data = [ 'content' => 'Changes saved!' ];
            }
        }
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:3;url=/profile" );
    }
}