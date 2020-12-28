<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\ComplexQuery;
use App\Core\Paginator;
use App\Models\User;

class ProfilController extends AControllerBase
{
    private const RESERVATIONS_PER_PAGE = 5;

    public function authorize(string $action)
    {
        return $this->app->getAuth()->isLogged();
    }

    public function index()
    {
        $postData = $this->app->getRequest()->getPost();
        $getData = $this->app->getRequest()->getGet();
        $data = null;
        if (isset($postData['oldPassword']) || isset($postData['newPassword']) || isset($postData['newPassword2']))
        {
            $data = $this->changePassword($postData);
        }

        if (isset($postData['name']))
        {
            $data = $this->changeName($postData);
        }

        if (isset($postData['surname']))
        {
            $data =  $this->changeSurname($postData);
        }

        if (isset($postData['phone']))
        {
            $data = $this->changePhone($postData);
        }

        $user = $this->app->getAuth()->getLoggedUser();
        if (is_null($data))
        {
            $data = ['data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated()]];
        }

        $numberOfRows = ComplexQuery::getUserHistoryCount($user->getEmail());
        $paginator = new Paginator(self::RESERVATIONS_PER_PAGE, $numberOfRows, "semestralka?c=Profil&page=");
        $page = !isset($_GET['page']) ? $_GET['page'] = 1 : $_GET['page'];
        $history= $paginator->getData($page, ComplexQuery::class, $user->getEmail(), 'getUserHistory');

        if (is_null($history) && ($numberOfRows == 0 && $page != 1))
        {
            $this->redirect("semestralka?c=NotFound");
        }
        else if (is_null($history) && $numberOfRows != 0)
        {
            $this->redirect("semestralka?c=NotFound");
        }

        if (isset($getData['page']))
            $_SESSION['page'] = $getData['page'];

        $data['history'] = $history;
        $data['paginator'] = $paginator;
        return $this->html($data);
    }

    private function changePassword($postData)
    {
        /** @var $user User */
        $user = User::getOne($this->app->getAuth()->getLoggedUser()->getEmail());
        if (password_verify($postData['oldPassword'], $user->getPassword()))
        {
            $passwordErrs = User::checkPassword($postData['newPassword']);
            $password2Errs = $this->comparePasswords($postData['newPassword'], $postData['newPassword2']);
            if (count($passwordErrs) > 0 || count($password2Errs) > 0)
            {
                return ['newPassword' => $passwordErrs, 'newPassword2' => $password2Errs ,
                           'data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated() ]];
            }
            else
            {
                $user->setPassword($postData['newPassword']);
                $user->update();
                return ['success' => true,
                                    'data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated() ]];
            }
        }
        else
        {
            return ['oldPassword' => ['Zadané heslo je nesprávne'],
                'data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated() ]];
        }
    }

    private function changeName($postData)
    {
        $nameErrs = User::checkName($postData['name']);
        $user = $this->app->getAuth()->getLoggedUser();
        if (count($nameErrs) > 0)
        {
            return ['nameErrors' => $nameErrs ,
                                'data' => ['name' => $postData['name'], 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated() ] ];
        }

        $user->setName(htmlspecialchars($postData['name']));
        $user->update();
        $this->app->getAuth()->updateLoggedUser();
        return  ['data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated()]];
    }

    private function changeSurname($postData)
    {
        $surnameErrs = User::checkSurname($postData['surname']);
        $user = $this->app->getAuth()->getLoggedUser();
        if (count($surnameErrs) > 0)
        {
            return ['surnameErrors' => $surnameErrs ,
                'data' => ['name' => $user->getName(), 'surname' => $postData['surname'], 'phone' => $user->getPhoneFormated()]];
        }

        $user->setSurname(htmlspecialchars($postData['surname']));
        $user->update();
        $this->app->getAuth()->updateLoggedUser();
        return ['data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated()]];
    }

    private function changePhone($postData)
    {
        $phoneErrs = User::checkPhone($postData['phone']);
        $user = $this->app->getAuth()->getLoggedUser();
        if (count($phoneErrs) > 0)
        {
            return ['phoneErrors' => $phoneErrs ,
                'data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $postData['phone'] ]];
        }

        $phone = $postData['phone'];
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('+', '', $phone);
        $user->setPhone($phone);
        $user->update();
        $this->app->getAuth()->updateLoggedUser();
        return  ['data' => ['name' => $user->getName(), 'surname' => $user->getSurname(), 'phone' => $user->getPhoneFormated()]];
    }

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