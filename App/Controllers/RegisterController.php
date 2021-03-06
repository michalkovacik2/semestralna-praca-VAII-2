<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Models\User;

/**
 * Class RegisterController represents a controller for register page
 * @package App\Controllers
 */
class RegisterController extends AControllerBase
{
    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function index()
    {
        $postData = $this->app->getRequest()->getPost();
        if (empty($postData))
        {
            return $this->html(null);
        }

        $name = $postData['name'];
        $surname = $postData['surname'];
        $email = $postData['email'];
        $phone = $postData['phone'];
        $password = $postData['password'];
        $passwordAgain = $postData['password2'];

        $errs = $this->isValid($name, $surname, $email, $phone, $password, $passwordAgain);
        if (is_null($errs))
        {
            //is valid
            $name = htmlspecialchars($name);
            $surname = htmlspecialchars($surname);
            $password = password_hash($password, PASSWORD_DEFAULT);
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('+', '', $phone);

            $u = new User($email, $name, $surname, $phone,
                $password, date('Y-m-d H:i:s'), null, 'U');
            $u->insert();
            return $this->html(['data' => null, 'logged'=> true, 'errors' => null]);
        }
        else
        {
            $name = htmlspecialchars($name);
            $surname = htmlspecialchars($surname);
            return $this->html(['data' => ['name' => $name, 'surname' => $surname, 'email' => $email,
                'phone' => $phone, 'password' => $password, 'password2' => $passwordAgain],
                'errors' => $errs]);
        }
    }

    /**
     * Method used to check if user data is valid
     * @param $name
     * @param $surname
     * @param $email
     * @param $phone
     * @param $password
     * @param $passwordAgain
     * @return array|null
     * @throws \Exception
     */
    private function isValid($name, $surname, $email, $phone, $password, $passwordAgain)
    {
        $nameErrs = User::checkName($name);
        $surnameErrs = User::checkSurname($surname);
        $emailErrs = User::checkEmail($email);
        $phoneErrs = User::checkPhone($phone);
        $passwordErrs = User::checkPassword($password);

        $password2Errs = $this->comparePasswords($password, $passwordAgain);

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

    /**
     * Method used to compare entered passwords if they are the same
     * @param $password
     * @param $passwordAgain
     * @return array
     */
    private function comparePasswords($password, $passwordAgain)
    {
        $password2Errs = [];
        if (empty($passwordAgain))
        {
            $password2Errs[] = "Prosím zopakujte heslo";
        }
        else
        {
            if ($passwordAgain != $password)
                $password2Errs[] = "Heslá sa musia zhodovať";
        }
        return $password2Errs;
    }
}