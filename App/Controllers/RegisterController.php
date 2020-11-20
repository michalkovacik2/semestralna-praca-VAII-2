<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Models\User;

class RegisterController extends AControllerBase
{
    private const MAX_SIZE_NAME = 30;
    private const MAX_SIZE_SURNAME = 50;
    private const MAX_SIZE_EMAIL = 320;
    private const PHONE_DIGITS = 12;
    private const MIN_PASSWORD_LENGTH = 8;

    public function register()
    {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $passwordAgain = $_POST['password2'];

        $errs = $this->isValid($name, $surname, $email, $phone, $password, $passwordAgain);
        if (is_null($errs))
        {
            $password = hash('sha256', $password);
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('+', '', $phone);

            $u = new User($email, $name, $surname, $phone,
                          $password, date('Y-m-d H:i:s'), null, 'U');
            $u->insert();
            return null;
        }
        else
        {
            return ['data'   => ['name' => $name, 'surname' => $surname, 'email' => $email,
                                 'phone' => $phone, 'password' => $password, 'password2' => $passwordAgain],
                    'errors' => $errs];
        }
    }

    public function index()
    {
        return "";
    }

    private function isValid($name, $surname, $email, $phone, $password, $passwordAgain)
    {
        $nameErrs = $this->validateName($name);
        $surnameErrs = $this->validateSurname($surname);
        $emailErrs = $this->validateEmail($email);
        $phoneErrs = $this->validatePhone($phone);
        $passwordErrs = $this->validatePassword($password);
        $password2Errs = $this->validatePassword2($password, $passwordAgain);

        if (count($nameErrs) > 0 || count($surnameErrs) > 0 || count($emailErrs) > 0 || count($phoneErrs) > 0
            || count($passwordErrs) > 0 || count($password2Errs) > 0)
        {
            return ['name' => $nameErrs, 'surname' => $surnameErrs, 'email' => $emailErrs, 'phone' => $phoneErrs,
                     'password' => $passwordErrs, 'password2' => $password2Errs];
        }
        else
        {
            return null;
        }

    }

    private function validateName($name)
    {
        $nameErrs = [];
        if (empty($name))
        {
            $nameErrs[] = "Prosim zadajte meno";
        }
        else
        {
            if (strlen($name) > self::MAX_SIZE_NAME)
                $nameErrs[] = "Meno nesmie byt dlhsie ako " . self::MAX_SIZE_NAME;

            if (preg_match('/[^A-Za-z-]/', $name))
                $nameErrs[] = "Meno smie obsahovat iba pismena";
        }
        return $nameErrs;
    }

    private function validateSurname($surname)
    {
        $surnameErrs = [];
        if (empty($surname))
        {
            $surnameErrs[] = "Prosim zadajte priezvisko";
        }
        else
        {
            if (strlen($surname) > self::MAX_SIZE_SURNAME)
                $surnameErrs[] = "Priezvisko nesmie byt dlhsie ako " . self::MAX_SIZE_SURNAME;

            if (preg_match('/[^A-Za-z-]/', $surname))
                $surnameErrs[] = "Priezvisko smie obsahovat iba pismena";
        }
        return $surnameErrs;
    }

    private function validateEmail($email)
    {
        $emailErrs = [];
        if (empty($email))
        {
            $emailErrs[] = "Prosim zadajte email";
        }
        else
        {
            if (strlen($email) > self::MAX_SIZE_EMAIL)
                $emailErrs[] = "Email nemoze byt dlhsi ako " . self::MAX_SIZE_EMAIL;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $emailErrs[] = "Zadany email nie je spravny";

            if (User::containsKey($email))
                $emailErrs[] = "Iny pouzivatel uz ma rovnaky email, zvolte iny";
        }
        return $emailErrs;
    }

    private function validatePhone($phone)
    {
        $phoneErrs = [];
        if (empty($phone))
        {
            $phoneErrs[] = "Prosim zadajte telefonne cislo";
        }
        else
        {
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('+', '', $phone);
            if (strlen($phone) != self::PHONE_DIGITS || preg_match('/[^0-9]/', $phone))
                $phoneErrs[] = "Cislo musi pozostavat z 12 cislic";

            if(substr($phone, 0, 3) != "421")
                $phoneErrs[] = "Cislo musi mat format +421 alebo 421";
        }
        return $phoneErrs;
    }

    private function validatePassword($password)
    {
        $passwordErrs = [];
        if (empty($password))
        {
            $passwordErrs[] = "Prosim zadajte heslo";
        }
        else
        {
            if (strlen($password) < self::MIN_PASSWORD_LENGTH)
                $passwordErrs[] = "Dlzka hesla musi byt aspon 8 znakov";

            if (!(preg_match('/[^0-9]/', $password) && preg_match('/[0-9]/', $password)))
                $passwordErrs[] = "Heslo musi obsahovat lubovolne znaky a aspon jednu cislicu";
        }
        return $passwordErrs;
    }

    private function validatePassword2($password, $passwordAgain)
    {
        $password2Errs = [];
        if (empty($passwordAgain))
        {
            $password2Errs[] = "Prosim zopakujte heslo";
        }
        else
        {
            if ($passwordAgain != $password)
                $password2Errs[] = "Hesla sa musia zhodovat";
        }
        return $password2Errs;
    }
}