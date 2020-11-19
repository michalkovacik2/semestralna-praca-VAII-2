<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\KeyNotFoundException;
use App\Models\User;


class LoginController extends AControllerBase
{
    public function index()
    {
        if (!isset($_POST['email']) && !isset($_POST['password']))
            return null;

        try
        {
            $user = User::getOne($_POST['email']);
        }
        catch (KeyNotFoundException $e)
        {
            return ['email' => $_POST['email'], 'password' => $_POST['password']];
        }

        if ($this->passwordCorrect($user, $_POST['password']))
        {
            session_start();
            $_SESSION['user'] = $user;
            $this->redirectTo("MainPage");
        }

        return ['email' => $_POST['email'], 'password' => $_POST['password']];

    }

    /**
     * @param User $user
     * @param $enteredPassword
     * @return mixed
     */
    private function passwordCorrect($user, $enteredPassword)
    {
        $hashedPassword = hash('sha256', $enteredPassword);
        return hash_equals($user->getPassword(), $hashedPassword);
    }
}