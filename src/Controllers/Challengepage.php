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
        $data['links'] = [
            ['href' => '/newchallenge', 'text' => 'Challenge Members'],
            ['href' => '/challengemanagement', 'text' => 'Manage Challenges'],
        ];

        $html = $this->renderer->render('Challengepage', $data);
        $this->response->setContent($html);
    }

    public function newChallenge()
    {
        $data['id'] = $_SESSION['userID'];
        $data['nickName'] = $_SESSION['nickName'];
        $data['profilePic'] = (empty($_SESSION['profilePic']) ? 'images/profileImages/default.jpg' : $_SESSION['profilePic']);
        $html = $this->renderer->render('ChallengeNewpage', $data);
        $this->response->setContent($html);
    }

    public function sendChallenge()
    {
        $params = $this->request->getParameters();
        $nick = $_SESSION['nickName'];
        $userNickNames = explode(',', $params['membersToChallenge']);
        $boardSize = $params['boardSize'] . 'x' . $params['boardSize'];
        $difficulty = $params['difficulty'];
        $duration = $params['duration'];

        var_dump($userNickNames);
        $db = $this->commonFunctions->getDatabase();

        $sql = "INSERT INTO ChallengeApproval VALUES(null, '$nick', '$duration', '$boardSize', '$difficulty', null)";
        $result = $db->execute($sql);
        $challengeId = $result->insertID();
        $approved = 1;
        $sql = "INSERT INTO UserChallengeApproval VALUES('$challengeId', '$nick', '$approved')";
        $result = $db->execute($sql);
        if ($result) {
            for ($i = 1; $i < count($userNickNames); $i++) {
                $approved = 0;
                $sql = "INSERT INTO UserChallengeApproval VALUES('$challengeId', '$userNickNames[$i]', '$approved')";
                $db->execute($sql);

            }
            $data['content'] = 'Challenge sent!';
        }
        else
        {
            $data['content'] = 'Error sending, challenge please try again!';
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:2;url=/challengemanagement" );
    }

    public function manageChallenges()
    {
        $data = [];
        $nick = $_SESSION['nickName'];

        //*********************************************
        // Collect results for all declined challenge history...
        $sqlHistory = "select group_concat( ( case when UserDeclinedChallenge.challengerNickName = '$nick' then 'You' else UserDeclinedChallenge.challengerNickName end ) separator ', ' ) as 'opponents', dateDeclined, group_concat( (case when UserDeclinedChallenge.declined = true then UserDeclinedChallenge.challengerNickName else '' end ) separator '' ) as declinedBy, ( case when DeclinedChallenge.challengerNickName = '$nick' then 'You' else DeclinedChallenge.challengerNickName end ) as challengerNickName
                    from UserDeclinedChallenge 
                    inner join DeclinedChallenge on
                    UserDeclinedChallenge.declinedChallengeID = DeclinedChallenge.declinedChallengeID
                    where UserDeclinedChallenge.declinedChallengeID in ( select declinedChallengeID from UserDeclinedChallenge where challengerNickName = '$nick' )
                    group by UserDeclinedChallenge.declinedChallengeID";

        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sqlHistory);
        $result = $result->fetchResult();
        $challengeHistory = [];
        $count = 0;
        while ($row = $result->fetch_assoc())
        {
            $challengeHistory['DC'.$count] = $row;
            $count++;
        }
        $data['declined'] = $challengeHistory;

        //********************************************************
        // Collect results for all challenges awaiting approval...
        $sqlPending = "select ChallengeApproval.challengeApprovalID, ( case when challengerNickName = '$nick' then 'You' else challengerNickName end ) as 'challengerNickName', rank, group_concat( ( case when userNickName = '$nick' then 'You' else userNickName end ) separator ', ' ) as 'opponents', concat_ws(' ', duration, 'Day(s)') as 'duration', userApproval
                from UserChallengeApproval
                inner join ChallengeApproval on
                UserChallengeApproval.challengeApprovalID = ChallengeApproval.challengeApprovalID
                inner join User on
                ChallengeApproval.challengerNickName = User.nickName
                where UserChallengeApproval.challengeApprovalID in ( select challengeApprovalID from UserChallengeApproval where userNickName = '$nick' )
                group by UserChallengeApproval.challengeApprovalID";

        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sqlPending);
        $result = $result->fetchResult();
        $challengePending = [];
        $count = 0;
        while ($row = $result->fetch_assoc())
        {
            $challengeId = $row['challengeApprovalID'];
            $sqlApproved = "select userApproval from UserChallengeApproval where challengeApprovalID = '$challengeId' and userNickName = '$nick'";
            $resultApproved = $db->execute($sqlApproved);
            $resultApproved = $resultApproved->fetch();
            $row['userApproval'] = $resultApproved['userApproval'];
            $challengePending['PC'.$count] = $row;
            $count++;
        }
        $data['pending'] = $challengePending;

        //*********************************************
        // Collect results for all challenge history...
        $sqlHistory = "select group_concat( ( case when userNickName = '$nick' then 'You' else userNickName end ) separator ', ' ) as 'opponents', dateArchived, boardSize, ( case difficulty when 4 then 'Easy' when 5 then 'Normal' else 'Hard' end) as 'difficulty', ( case when winnerNickName = '$nick' then 'You' else winnerNickName end ) as 'winner'
                    from UserChallengeHistory 
                    inner join ChallengeHistory on
                    UserChallengeHistory.challengeHistoryID = ChallengeHistory.challengeHistoryID
                    inner join SudokuBoard on
                    ChallengeHistory.boardID = SudokuBoard.boardID
                    where UserChallengeHistory.challengeHistoryID in ( select challengeHistoryID from UserChallengeHistory where userNickName = '$nick' )
                    group by UserChallengeHistory.challengeHistoryID;";

        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sqlHistory);
        $result = $result->fetchResult();
        $challengeHistory = [];
        $count = 0;
        while ($row = $result->fetch_assoc())
        {
            $challengeHistory['HC'.$count] = $row;
            $count++;
        }
        $data['history'] = $challengeHistory;

        //*********************************************
        // Collect results for all active challenges...
        $sqlActive = "select ActiveChallenge.activeChallengeID, group_concat( ( case when userNickName = '$nick' then 'You' else userNickName end ) separator ', ' ) as 'opponents', concat_ws( ' ', dateEnd - dateStart, 'Day(s)' ) as 'timeleft', forfeited from UserActiveChallenge
                    inner join ActiveChallenge on
                    UserActiveChallenge.activeChallengeID = ActiveChallenge.activeChallengeID
                    where UserActiveChallenge.activeChallengeID in ( select UserActiveChallenge.activeChallengeID from UserActiveChallenge where userNickName = '$nick' ) group by UserActiveChallenge.activeChallengeID";

        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sqlActive);
        $result = $result->fetchResult();
        $challengeActive = [];
        $count = 0;
        while ($row = $result->fetch_assoc())
        {
            $challengeId = $row['activeChallengeID'];
            $sqlApproved = "select forfeited from UserActiveChallenge where activeChallengeID = '$challengeId' and userNickName = '$nick'";
            $resultForfeited = $db->execute($sqlApproved);
            $resultForfeited = $resultForfeited->fetch();
            $row['forfeited'] = $resultForfeited['forfeited'];
            $challengeActive['AC'.$count] = $row;
            $count++;
        }
        $data['active'] = $challengeActive;

        $html = $this->renderer->render('ChallengeManagementpage', $data);
        $this->response->setContent($html);
    }

    public function acceptDeclineChallenge()
    {
        $params = $this->request->getParameters();
        $nick = $_SESSION['nickName'];
        $challengeId = $params['pendingId'];

        //*************************************************
        // Modify a challenge if it is declined or accepted
        if (isset($params['Accept']))
        {
            $this->response->setContent('ACCEPT');
            $sql = "UPDATE UserChallengeApproval SET userApproval = true WHERE userNickName = '$nick' AND challengeApprovalID = '$challengeId'";
            $action = 'accepted';
        }
        else if (isset($params['Decline']))
        {
            $this->response->setContent('DECLINE');
            $sql = "UPDATE UserChallengeApproval SET userApproval = false WHERE userNickName = '$nick' AND challengeApprovalID = '$challengeId'";
            $action = 'declined';
        }

        $storedProc = "call deleteApprovedChallenges()";
        $db = $this->commonFunctions->getDatabase();
        if ($db->execute($sql))
        {
            $db->execute($storedProc);
            $data['content'] = 'Challenge ' . $action;
        }
        else
        {
            $data['content'] = 'Challenge ' . $action . ' action failed, please try again!';
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:1;url=/challengemanagement" );
    }

    public function forfeitedChallenge()
    {
        $params = $this->request->getParameters();
        $nick = $_SESSION['nickName'];
        $challengeId = $params['forfeitId'];
        $data['content'] = 'Error forfeiting challenge, please try again!';

        if (isset($params['Forfeit']))
        {
            $sql = "update UserActiveChallenge set forfeited = '1', completionTime = '0' where userNickName = '$nick' and activeChallengeID = '$challengeId'";
            $db = $this->commonFunctions->getDatabase();
            $result = $db->execute($sql);
            $storedProc = "call deleteFinishedChallenges()";
            $db->execute($storedProc);
            if ($result)
            {
                $data['content'] = 'Challenge forfeited.';
            }
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:1;url=/challengemanagement" );
    }
}