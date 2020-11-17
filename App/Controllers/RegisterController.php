<?php

namespace App\Controllers;

use App\Core\AControllerBase;

class RegisterController extends AControllerBase
{
    public function register()
    {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $passwordAgain = $_POST['password2'];


    }

    public function index()
    {
        return "";
    }
}