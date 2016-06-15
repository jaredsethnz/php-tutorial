<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 15/06/16
 * Time: 10:52 AM
 */

namespace Forum\Controllers;

use Forum\Template\FrontEndRenderer;
use Http\Request;
use Http\Response;
use Forum\db\CommonFunctions;

class Async
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

    public function memberSearch()
    {
        $method = $this->request->getMethod();
        if ($method == 'POST')
        {
            $params = $this->request->getParameters();
            $searchNick = $params['searchNick'];
            $sql = "SELECT userID, nickName, challengeable, rank, profilePic FROM User WHERE nickName LIKE '$searchNick%'";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->query($sql);
            $result = $result->fetchResult();

            $html ='';
            while ($row = $result->fetch_assoc())
            {
                $nick = $row['nickName'];
                if ($nick != $_SESSION['nickName']) {
                    $rank = $this->buildRankImg($row['rank']);
                    $pic = $this->buildProfilePic($row['profilePic']);
                    $challengeable = $this->buildChallengeForm($row['challengeable'], $row['userID'], $pic, $nick);

                    $html .= "<tr> <td><img src=" . $pic . " width='15px' height='15px'>$nick</td> <td>$rank</td> <td>$challengeable</td> </tr>";
                }
            }
        }
        $this->response->setContent($html);
    }

    protected function buildProfilePic($picPath)
    {
        if (empty($picPath))
        {
            return 'images/profileImages/default.jpg';
        }
        else
        {
            return $picPath;
        }
    }

    protected function buildRankImg($rankCount)
    {
        $rankStars = "Rank ";
        for ($i = 0; $i < $rankCount; $i++)
        {
            $rankStars .= "<img src='images/star.png' width='15px' height='15px' />";
        }
        return $rankStars;
    }

    protected function buildChallengeForm($challengeable, $id, $pic, $nick)
    {
        $html = "";
        if ($challengeable)
        {
            $html .= "<form class='challengeUserForm' method='POST'><input type='hidden' name='userId' value=".$id."><input type='hidden' name='userNickName' value=".$nick."><input type='hidden' name='profilePic' value=".$pic."><input type='submit' name='Submit' value='Challenge'></form>";
        }
        else
        {
            $html .= "Not Challengeable";
        }
        return $html;
    }

}
