<?php

namespace App\Controllers;
use App\Core\AControllerBase;

/**
 * Class AboutController represents controller for about page.
 * @package App\Controllers
 */
class AboutController extends AControllerBase
{
    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     */
    public function index()
    {
        return $this->html(null);
    }
}