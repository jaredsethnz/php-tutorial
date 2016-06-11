<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 4/06/16
 * Time: 9:11 PM
 */
namespace Forum\Page;

use Exception;

class InvalidPageException extends Exception
{
    public function __construct($slug, $code = 0, Exception $previous = null)
    {
        $message = "No page with the slug `$slug` was found";
        parent::__construct($message, $code, $previous);
    }
}