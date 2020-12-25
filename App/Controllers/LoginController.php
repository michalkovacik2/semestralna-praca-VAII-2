<?php

namespace App\Controllers;
use App\Core\AControllerBase;

class LoginController extends AControllerBase
{
    public function index()
    {
        $postData = $this->app->getRequest()->getPost();

        if ($this->app->getAuth()->isLogged())
        {
            $this->redirect("?c=MainPage");
        }

        if (empty($postData))
        {
            return $this->html(null);
        }

        if ($this->app->getAuth()->login($postData['email'], $postData['password']))
        {
            $this->redirect('?c=MainPage');
        }

        return $this->html(['email' => $_POST['email'], 'password' => null]);
    }

}