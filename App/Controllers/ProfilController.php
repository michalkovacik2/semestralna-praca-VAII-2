<?php

namespace App\Controllers;
use App\Core\AControllerBase;

class ProfilController extends AControllerBase
{
    private const MIN_PASSWORD_LENGTH = 8;
    public function index()
    {
        if (!$this->app->getAuth()->isLogged())
        {
            $this->redirect("?c=MainPage");
        }

        if (isset($_POST['oldPassword']) || isset($_POST['newPassword']) || isset($_POST['newPassword2']))
        {
            $user = $_SESSION['user'];
            if($this->passwordCorrect($user, $_POST['oldPassword']))
            {
                $passwordErrs = $this->validatePassword($_POST['newPassword']);
                $password2Errs = $this->validatePassword2($_POST['newPassword'], $_POST['newPassword2']);
                if (count($passwordErrs) > 0 || count($password2Errs) > 0)
                {
                    return ['newPassword' => $passwordErrs, 'newPassword2' => $password2Errs ];
                }
                else
                {
                    $user->setPassword(hash('sha256', $_POST['newPassword']));
                    $user->update();
                    return ['success' => true];
                }
            }
            else
            {
                return ['oldPassword' => ['Zadane heslo je nespravne']];
            }
        }

        return null;
    }

    private function passwordCorrect($user, $enteredPassword)
    {
        $hashedPassword = hash('sha256', $enteredPassword);
        return hash_equals($user->getPassword(), $hashedPassword);
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