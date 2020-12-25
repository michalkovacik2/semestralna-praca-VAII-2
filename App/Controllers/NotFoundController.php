<?php

namespace App\Controllers;

use App\Core\AControllerBase;

class NotFoundController extends AControllerBase
{
    public function index()
    {
        return $this->html(null);
    }
}