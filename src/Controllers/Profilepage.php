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
                'profilepic' => $result['profilePic']
            ];
            echo "<img src=profileImages/thor.jpg />";
        }
        else
        {
            $data = [];
        }

        $html = $this->renderer->render('Profilepage', $data);
        $this->response->setContent($html);
    }

    public function saveChanges()
    {
        $method = $this->request->getMethod();
        $uploadDir = __DIR__ . '/../profileImages/';

        if($method == 'POST') {
            $fileName = $_FILES['photo']['name'];
            $tmpName  = $_FILES['photo']['tmp_name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];
            var_dump($tmpName);

            $filePath = $uploadDir . $fileName;

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
            $db->execute($sql);
        }
    }
}