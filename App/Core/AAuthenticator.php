<?php

namespace App\Core;

use App\Models\User;

abstract class AAuthenticator
{
    protected static $instance;

    static public function getInstance(): AAuthenticator
    {

        if (AAuthenticator::$instance == null) {
            AAuthenticator::$instance = new static();
        }
        return AAuthenticator::$instance;
    }

    abstract function login($userLogin, $pass);

    abstract function logout();

    abstract function getLoggedUser(): User;

    abstract function isLogged();

}