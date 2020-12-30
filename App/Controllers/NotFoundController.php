<?php

namespace App\Controllers;
use App\Core\AControllerBase;

/**
 * Class NotFoundController controller that displays 404 not found site
 * @package App\Controllers
 */
class NotFoundController extends AControllerBase
{
    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function index()
    {
        return $this->html(null);
    }
}