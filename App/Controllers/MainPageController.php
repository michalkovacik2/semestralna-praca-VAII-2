<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\KeyNotFoundException;
use App\Models\News;

class MainPageController extends AControllerBase
{
    private const NEWS_PER_PAGE = 4;
    private const MAX_TITLE_LEN = 30;
    private const MAX_FILE_SIZE = 14000000;

    public function index()
    {
        $numRows = News::getNumberOfRows();
        $numSites = intdiv($numRows, self::NEWS_PER_PAGE);
        $numSites = $numRows % self::NEWS_PER_PAGE != 0 ? $numSites + 1 : $numSites;

        if (!isset($_GET['page']))
        {
            $_GET['page'] = 1;
            return [ 'news' => News::getFromOrderBy(0, self::NEWS_PER_PAGE, 5),
                'numberOfNews' => $numSites ];
        }
        else
        {
            if ($_GET['page'] - 1 < 0 || $_GET['page'] > $numSites)
            {
                return null;
            }
            return [ 'news' => News::getFromOrderBy(($_GET['page'] - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE, 5),
                'numberOfNews' => $numSites ];
        }

    }

    public function logout()
    {
        $_SESSION[] = array();
        if(ini_get("session.use_cookies"))
        {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirectTo("MainPage");
    }

    public function add()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']->getPermissions() != 'A' )
        {
            $this->redirectTo("MainPage");
        }

        if (!isset($_POST['title']) && !isset($_POST['text']))
        {
            return null;
        }


        $file = $_FILES['file'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        $res = $this->validate($title, $text, $file);
        if(is_null($res))
        {
            $image64 = base64_encode(file_get_contents($file['tmp_name']));
            $title = htmlspecialchars($title);
            $text = htmlspecialchars($text);
            $u = new News($title, $text, $image64, date('Y-m-d H:i:s'), $_SESSION['user']->getEmail());
            $u->save();
            $this->redirectTo("MainPage");
        }
        else
        {
            return ['data'   => ['title' => $title, 'text' => $text, 'file' => $file],
                'errors' => $res];
        }
        return null;
    }

    public function delete()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']->getPermissions() != 'A' )
        {
            $this->redirectTo("MainPage");
        }

        if (isset($_GET['id']))
        {
            $news = null;
            try
            {
                $news = News::getOne($_GET['id']);
            }
            catch (KeyNotFoundException $ex)
            {
                $this->redirectTo("MainPage");
            }

            $actualPage = $_GET['page'];
            $numElements = $_GET['numElements'];

            $news->delete();
            if ($numElements > 1)
            {
                $this->redirectTo("MainPage&page=". $actualPage);
            }
            else
            {
                $actualPage = ($actualPage == 1 ? 1 : $actualPage - 1);
                $this->redirectTo("MainPage&page=". $actualPage);
            }
        }
        $this->redirectTo("MainPage");
        die();
    }

    public function edit()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']->getPermissions() != 'A' )
        {
            $this->redirectTo("MainPage");
        }

        if (!isset($_GET['id']))
        {
            $this->redirectTo("MainPage");
        }

        $news = null;
        $errs = null;
        try
        {
            $news = News::getOne($_GET['id']);
        }
        catch (KeyNotFoundException $ex)
        {
            $this->redirectTo("MainPage");
        }

        //Bola vykonana zmena uzivatelom
        if (isset($_POST['title']) || isset($_POST['text']))
        {
            $news->setTitle(htmlspecialchars($_POST['title']));
            $news->setText(htmlspecialchars($_POST['text']));
            $titleErr = $this->validateTitle($news->getTitle());
            $textErr = $this->validateText($news->getText());
            $fileErr = [];
            if (isset($_FILES['file']) && $_FILES['file']['size'] != 0)
            {
                $fileErr = $this->validateFile($_FILES['file']);
            }

            if (count($titleErr) > 0 || count($textErr) > 0 || count($fileErr) > 0)
            {
                $errs = ['title' => $titleErr, 'text' => $textErr, 'file' => $fileErr];
            }
            else
            {
                if ($_FILES['file']['size'] != 0)
                    $news->setPicture(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));

                //$news->setCreationDate(date('Y-m-d H:i:s'));
                $news->setEmail($_SESSION['user']->getEmail());
                $news->save();
                $this->redirectTo("MainPage");
            }
        }

        return ['data' => $news, 'errors' => $errs];
    }

    private function validate($title, $text, $file)
    {
        $titleErrs = $this->validateTitle($title);
        $textErrs = $this->validateText($text);
        $fileErrs = $this->validateFile($file);

        if (count($titleErrs) > 0 || count($textErrs) > 0 || count($fileErrs) > 0)
        {
            return ['title' => $titleErrs, 'text' => $textErrs, 'file' => $fileErrs];
        }

        return null;
    }

    private function validateText($text)
    {
        $textErrs = [];
        if (empty($text))
        {
            $textErrs[] = 'Prosim zadajte text';
        }
        return $textErrs;
    }

    private function validateTitle($title)
    {
        $titleErrs = [];
        if (empty($title))
        {
            $titleErrs[] = 'Prosim zadajte nadpis';
        }
        else
        {
            if (strlen($title) > self::MAX_TITLE_LEN)
            {
                $titleErrs[] = 'Nadpis moze mat maximalne 30 znakov';
            }
        }
        return $titleErrs;
    }

    private function validateFile($file)
    {
        $fileErrs = [];
        if (!isset($file['error']))
        {
            $fileErrs[] = 'Prosim nahrajte obrazok';
            return $fileErrs;
        }
        else
        {
            if (is_array($file['error']))
            {
                $fileErrs[] = 'Nahrajte iba jeden subor';
                return $fileErrs;
            }

            switch ($file['error'])
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $fileErrs[] = 'Prosim nahrajte obrazok';
                    return $fileErrs;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $fileErrs[] = 'Obrazok je prilis velky';
                    return $fileErrs;
                default:
                    throw new \RuntimeException('File upload error');
            }

            $finfo = finfo_open( FILEINFO_MIME_TYPE );
            $mtype = finfo_file( $finfo, $file['tmp_name'] );
            finfo_close( $finfo );
            if ( $mtype != ( "image/png" ) && $mtype != ( "image/jpeg" ))
            {
                $fileErrs[] = 'Obrazok musi byt png alebo jpeg';
                return $fileErrs;
            }

            $image_base64 = base64_encode(file_get_contents($file['tmp_name']));
            if(strlen($image_base64) > self::MAX_FILE_SIZE)
            {
                $fileErrs[] = 'Obrazok je prilis velky';
            }
        }

        return $fileErrs;
    }

}