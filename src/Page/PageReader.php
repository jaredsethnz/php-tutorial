<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 4/06/16
 * Time: 9:01 PM
 */
namespace Example\Page;

interface PageReader
{
    public function readBySlug($slug);
}