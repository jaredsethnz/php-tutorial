<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 4/06/16
 * Time: 8:54 PM
 */
namespace Forum\Controllers;

use Forum\db\CommonFunctions;
use Forum\Page\InvalidPageException;
use Forum\Template\FrontEndRenderer;
use Http\Response;
use Forum\Page\PageReader;

class Page
{
    private $response;
    private $renderer;
    private $pageReader;
    private $commonFunctions;

    public function __construct(
        Response $response,
        FrontEndRenderer $renderer,
        PageReader $pageReader,
        CommonFunctions $cf
    ) {
        $this->response = $response;
        $this->renderer = $renderer;
        $this->pageReader = $pageReader;
        $this->commonFunctions = $cf;
    }
    public function show($params)
    {
        $slug = $params['slug'];
        try {
            $data['content'] = $this->pageReader->readBySlug($slug);
        } catch(InvalidPageException $e) {
            $this->response->setStatusCode(404);
            return $this->response->setContent('404 - Page not found');
        }
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
    }
}