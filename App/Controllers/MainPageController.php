<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\KeyNotFoundException;
use App\Core\Paginator;
use App\Models\News;

class MainPageController extends AControllerBase
{
    private const NEWS_PER_PAGE = 4;

    public function index()
    {
        $paginator = new Paginator(self::NEWS_PER_PAGE, News::getNumberOfRows(), "semestralka?c=MainPage&page=");

        $page = !isset($_GET['page']) ? $_GET['page'] = 1 : $_GET['page'];
        $news = $paginator->getData($page, News::class, 5, 'getFromOrderBy');
        if (is_null($news))
        {
            $this->redirect("?c=NotFound");
        }
        return $this->html([ 'news' => $news, 'paginator' => $paginator ]);
    }

    public function logout()
    {
        $this->app->getAuth()->logout();
        $this->redirect("?c=MainPage");
    }

    public function add()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("?c=MainPage");
        }

        $postData = $this->app->getRequest()->getPost();
        if (empty($postData))
        {
            return $this->html(null);
        }

        $filesData = $this->app->getRequest()->getFiles();
        $file = $filesData['file'];
        $title = $postData['title'];
        $text = $postData['text'];

        $res = $this->validate($title, $text, $file);
        if(is_null($res))
        {
            $image64 = base64_encode(file_get_contents($file['tmp_name']));
            $title = htmlspecialchars($title);
            $text = htmlspecialchars($text);
            $u = new News($title, $text, $image64, date('Y-m-d H:i:s'), $_SESSION['user']->getEmail());
            $u->save();
            $this->redirect("?c=MainPage");
        }
        else
        {
            return $this->html(['data'   => ['title' => $title, 'text' => $text, 'file' => $file],
                                'errors' => $res]);
        }
        return $this->html(null);
    }

    public function delete()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("?c=MainPage");
        }

        $getData = $this->app->getRequest()->getGet();
        if (isset($getData['id']))
        {
            $news = null;
            try
            {
                $news = News::getOne($getData['id']);
            }
            catch (KeyNotFoundException $ex)
            {
                $this->redirect("?c=MainPage");
            }

            $actualPage = $getData['page'];
            $numElements = $getData['numElements'];

            $news->delete();
            if ($numElements > 1)
            {
                $this->redirect("?c=MainPage&page=". $actualPage);
            }
            else
            {
                $actualPage = ($actualPage == 1 ? 1 : $actualPage - 1);
                $this->redirect("?c=MainPage&page=". $actualPage);
            }
        }
        $this->redirect("?c=MainPage");
    }

    public function edit()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("?c=MainPage");
        }

        $postData = $this->app->getRequest()->getPost();
        $getData = $this->app->getRequest()->getGet();
        if (!isset($getData['id']))
        {
            return $this->html("?c=MainPage");
        }

        $news = null;
        $errs = null;
        try
        {
            $news = News::getOne($getData['id']);
        }
        catch (KeyNotFoundException $ex)
        {
            $this->redirect("?c=MainPage");
        }

        $filesData = $this->app->getRequest()->getFiles();
        //Bola vykonana zmena uzivatelom
        if (isset($postData['title']) || isset($postData['text']))
        {
            $news->setTitle(htmlspecialchars($postData['title']));
            $news->setText(htmlspecialchars($postData['text']));
            $titleErr = News::checkTitle($news->getTitle());
            $textErr = News::checkText($news->getText());
            $fileErr = [];
            if (isset($filesData['file']) && $filesData['file']['size'] != 0)
            {
                $fileErr = News::checkFile($filesData['file']);
            }

            if (count($titleErr) > 0 || count($textErr) > 0 || count($fileErr) > 0)
            {
                $errs = ['title' => $titleErr, 'text' => $textErr, 'file' => $fileErr];
            }
            else
            {
                if ($filesData['file']['size'] != 0)
                    $news->setPicture(base64_encode(file_get_contents($filesData['file']['tmp_name'])));

                //$news->setCreationDate(date('Y-m-d H:i:s'));
                $news->setEmail($this->app->getAuth()->getLoggedUser()->getEmail());
                $news->save();
                $this->redirect("?c=MainPage&page=". $_SESSION['page']);
            }
        }

        if (isset($getData['page']))
            $_SESSION['page'] = $getData['page'];

        return $this->html(['data' => $news, 'errors' => $errs]);
    }

    private function validate($title, $text, $file)
    {
        $titleErrs = News::checkTitle($title);
        $textErrs = News::checkText($text);
        $fileErrs = News::checkFile($file);

        if (count($titleErrs) > 0 || count($textErrs) > 0 || count($fileErrs) > 0)
        {
            return ['title' => $titleErrs, 'text' => $textErrs, 'file' => $fileErrs];
        }

        return null;
    }

}