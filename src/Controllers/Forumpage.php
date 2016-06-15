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

        $data['categories'] = [ 'one', 'two', 'three', 'four', 'five'];
        $data['threads'] = [ [1, 2, 3, 4, 5], [1, 2, 3], [1, 2], [1, 4, 5], [6, 4, 3, 6] ];

        $html = $this->renderer->render('ForumCategorypage', $data);
        $this->response->setContent($html);
    }
}