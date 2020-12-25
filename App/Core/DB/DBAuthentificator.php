<?php

namespace App\Core\DB;

use App\Core\AAuthenticator;
use App\Core\KeyNotFoundException;
use App\Models\User;

/**
 * Class DBAuthentificator
 * @package App\Auth
 */
class DBAuthentificator extends AAuthenticator
{
    /**
     * DBAuthentificator constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Tries to login user, returns true on success.
     * @param $login
     * @param $pass
     * @return bool
     * @throws \Exception
     */
    function login($login, $pass)
    {
        try
        {
            $user = User::getOne($_POST['email']);
        }
        catch (KeyNotFoundException $e)
        {
            return false;
        }

        if (password_verify($pass, $user->getPassword()))
        {
            $_SESSION['user'] = $user;
            return true;
        }

        return false;

    }

    /**
     * Logout current user
     */
    function logout()
    {
        if (isset($_SESSION['user']))
        {
            unset($_SESSION['user']);
            if(ini_get("session.use_cookies"))
            {
                $p = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
            }
            session_destroy();
        }
    }

    /**
     * Returns logged user
     * @return User
     */
    function getLoggedUser(): User
    {
        return $_SESSION['user'];
    }

    /**
     * Returns true is user is logged in.
     * @return bool
     */
    function isLogged()
    {
        return isset($_SESSION['user']) && $_SESSION['user'] != null;
    }
}