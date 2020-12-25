<?php


namespace App\Controllers;
use App\Core\AControllerBase;

class AboutController extends AControllerBase
{
    public function index()
    {
        return $this->html(null);
    }
}