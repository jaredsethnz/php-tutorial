<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 2/06/16
 * Time: 7:48 PM
 */

return [
    ['GET', '/login', ['Forum\Controllers\Loginpage', 'show']],
    ['POST', '/login', ['Forum\Controllers\Loginpage', 'authenticate']],
    ['GET', '/logout', ['Forum\Controllers\Loginpage', 'logout']],
    ['GET', '/registration', ['Forum\Controllers\Registration', 'show']],
    ['POST', '/registration/checkemail', ['Forum\Controllers\Registration', 'validateEmail']],
    ['POST', '/registration/checknickname', ['Forum\Controllers\Registration', 'validateUsername']],
    ['POST', '/registration', ['Forum\Controllers\Registration', 'signup']],
    ['GET', '/register/verify', ['Forum\Controllers\Registration', 'accountActivation']],
    ['GET', '/', ['Forum\Controllers\Homepage', 'show']],
    ['GET', '/forum', ['Forum\Controllers\Forumpage', 'showCategories']],
    ['POST', '/forum', ['Forum\Controllers\Forumpage', 'addThread']],
    ['GET', '/forum/{slug}', ['Forum\Controllers\Forumpage', 'showThread']],
    ['POST', '/forum/reply', ['Forum\Controllers\Forumpage', 'addReply']],
    ['POST', '/forum/post', ['Forum\Controllers\Forumpage', 'addPost']],
    ['GET', '/challenges', ['Forum\Controllers\Challengepage', 'show']],
    ['GET', '/challengemanagement', ['Forum\Controllers\Challengepage', 'manageChallenges']],
    ['POST', '/challengemanagementad', ['Forum\Controllers\ChallengePage', 'acceptDeclineChallenge']],
    ['POST', '/challengemanagementff', ['Forum\Controllers\ChallengePage', 'forfeitedChallenge']],
    ['GET', '/newchallenge', ['Forum\Controllers\ChallengePage', 'newChallenge']],
    ['POST', '/newchallenge', ['Forum\Controllers\ChallengePage', 'sendChallenge']],
    ['GET', '/profile', ['Forum\Controllers\Profilepage', 'show']],
    ['POST', '/profile', ['Forum\Controllers\Profilepage', 'saveChanges']],
    ['GET', '/{slug}', ['Forum\Controllers\Page', 'show']],
    ['POST', '/async/membersearch', ['Forum\Controllers\Async', 'memberSearch']],
    ['POST', '/async/memberautocomplete', ['Forum\Controllers\Async', 'memberAutoComplete']],
];