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

    public function showCategories()
    {
        $sql = "SELECT categoryName FROM Category";
        $db = $this->commonFunctions->getDatabase();
        $result = $db->query($sql);
        $result = $result->fetchResult();

        $categories = [];
        $catThreads = [];

        while ($rowCategory = $result->fetch_assoc())
        {
            $catName = $rowCategory['categoryName'];
            $sqlThreads = "SELECT * FROM Thread WHERE categoryName = '$catName'";
            $resultThreads = $db->query($sqlThreads);
            $resultThreads = $resultThreads->fetchResult();

            $threads = [];
            while ($rowThread = $resultThreads->fetch_assoc())
            {
                $threadId = $rowThread['threadID'];
                $sqlPost = "SELECT IFNULL((SELECT concat('Last post on ', postDate, ', by ', nickName) FROM Post where threadID = '$threadId' ORDER BY postDate DESC LIMIT 1), 'No posts...') as 'lastPost'";
                $resultPost = $db->query($sqlPost);
                $resultPost = $resultPost->fetch();

                if ($resultPost)
                {
                    $rowThread['lastPost'] = $resultPost['lastPost'];
                }
                array_push($threads, $rowThread);
            }
            array_push($catThreads, $threads);
            array_push($categories, $rowCategory);
        }
        $data['categories'] = $categories;
        $data['threads'] = $catThreads;
        $html = $this->renderer->render('ForumCategorypage', $data);
        $this->response->setContent($html);
    }

    public function showThread($params)
    {
        $slug = $params['slug'];
        $sqlPosts = "SELECT postID, Post.threadID, Post.nickName, postContent, postDate, Thread.title, User.challengeable, User.rank, User.profilePic FROM Post  INNER JOIN Thread on  Thread.threadID = Post.threadID INNER JOIN User on User.nickName = Post.nickName WHERE Post.threadID = '$slug'";
        $db = $this->commonFunctions->getDatabase();
        $resultPosts = $db->query($sqlPosts);
        $resultPosts = $resultPosts->fetchResult();

        $posts = [];
        $allReplies = [];
        $postCount = 0;
        while ($rowPost = $resultPosts->fetch_assoc())
        {
            $postID = $rowPost['postID'];
            $sqlReplies = "SELECT replyID, postID, Reply.nickName, replyContent, replyDate, User.challengeable, User.rank, User.profilePic FROM Reply INNER JOIN User on User.nickName = Reply.nickName WHERE postID = '$postID' ORDER BY replyDate ASC";
            $resultReply = $db->query($sqlReplies);
            $resultReply = $resultReply->fetchResult();

            $replies = [];
            while ($rowReply = $resultReply->fetch_assoc())
            {
                $rowReply['profilePic'] = empty($rowReply['profilePic']) ? 'images/profileImages/default.jpg' : ($rowReply['profilePic']);
                array_push($replies, $rowReply);
            }
            $rowPost['profilePic'] = empty($rowPost['profilePic']) ? 'images/profileImages/default.jpg' : ($rowPost['profilePic']);
            array_push($allReplies, $replies);
            array_push($posts, $rowPost);
            $postCount++;
        }
        $data['posts'] = $posts;
        $data['replies'] = $allReplies;
        $data['threadID'] = $slug;

        $html = $this->renderer->render('ForumThreadpage', $data);
        $this->response->setContent($html);
    }

    public function addThread()
    {
        $params = $this->request->getParameters();
        $catName = $params['categoryName'];
        $nick = $_SESSION['nickName'];
        $title = $params['newThreadName'];
        $postContent = $params['post'];

        $sql = "INSERT INTO Thread VALUES (null, '$catName', '$nick', '$title', current_timestamp())";
        $db = $this->commonFunctions->getDatabase();
        $resultThread = $db->execute($sql);

        $threadId = $resultThread->insertID();
        $sql = "INSERT INTO Post VALUES (null, '$threadId', '$nick', '$postContent', current_timestamp())";
        $resultPost = $db->execute($sql);

        $data['content'] = 'Error creating new thread, please try again..';
        if ($resultThread && $resultPost)
        {
            $data['content'] = 'New thread created successfully..';
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:2;url=/forum" );
    }

    public function addPost()
    {
        $params = $this->request->getParameters();
        $nick = $_SESSION['nickName'];
        $postContent = $params['postContent'];
        $threadId = $params['threadID'];

        $sql = "INSERT INTO Post VALUES (null, '$threadId', '$nick', '$postContent', current_timestamp())";
        $db = $this->commonFunctions->getDatabase();
        $result = $db->execute($sql);

        $data['content'] = 'Error creating new post, please try again..';
        if ($result)
        {
            $data['content'] = 'New post created successfully..';
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:1;url=/forum/".$threadId );
    }

    public function addReply()
    {
        $params = $this->request->getParameters();
        $nick = $_SESSION['nickName'];
        $postId = $params['postID'];
        $replyContent = $params['replyContent'];
        $threadId = $params['threadID'];


        $sql = "INSERT INTO Reply VALUES (null, '$postId', '$nick', '$replyContent', current_timestamp())";
        $db = $this->commonFunctions->getDatabase();
        $result = $db->execute($sql);
        $data['content'] = 'Error trying to submit reply, please try again..';
        if ($result)
        {
            $data['content'] = 'Reply submitted..';
        }

        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
        header( "refresh:1;url=/forum/".$threadId );
    }
}