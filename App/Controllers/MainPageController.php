<?php

namespace App\Controllers;
use App\Core\AControllerBase;

class MainPageController extends AControllerBase
{
    public function index()
    {
        return "";
    }

    public function logout()
    {
        $_SESSION[] = array();
        if(ini_get("session.use_cookies"))
        {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: semestralka?c=MainPage');
        die();
    }
}