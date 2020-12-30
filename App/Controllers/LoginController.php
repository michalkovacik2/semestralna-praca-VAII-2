<?php

namespace App\Controllers;
use App\Core\AControllerBase;

/**
 * Class LoginController represents controller for login page
 * @package App\Controllers
 */
class LoginController extends AControllerBase
{
    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     */
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