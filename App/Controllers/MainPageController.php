<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\KeyNotFoundException;
use App\Core\Paginator;
use App\Models\News;

/**
 * Class MainPageController represents controller for main page
 * @package App\Controllers
 */
class MainPageController extends AControllerBase
{
    private const NEWS_PER_PAGE = 4;

    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function index()
    {
        $paginator = new Paginator(self::NEWS_PER_PAGE, News::getNumberOfRows(), "semestralka?c=MainPage&page=");

        $page = !isset($_GET['page']) ? $_GET['page'] = 1 : $_GET['page'];
        $news = $paginator->getData($page, News::class, 5, 'getFromOrderBy');
        if (is_null($news))
        {
            $this->redirect("semestralka?c=NotFound");
        }
        return $this->html([ 'news' => $news, 'paginator' => $paginator ]);
    }

    /**
     * Represents action to logout user
     */
    public function logout()
    {
        $this->app->getAuth()->logout();
        $this->redirect("semestralka?c=MainPage");
    }

    /**
     * Action that adds a News to main page
     * Expected POST parameters 'title', 'text' and it also expects a FILE
     * @return \App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function add()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=MainPage");
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
            //If input is valid
            $image64 = base64_encode(file_get_contents($file['tmp_name']));
            $title = htmlspecialchars($title);
            $text = htmlspecialchars($text);
            $u = new News($title, $text, $image64, date('Y-m-d H:i:s'), $_SESSION['user']->getEmail());
            $u->save();
            $this->redirect("semestralka?c=MainPage");
        }
        else
        {
            return $this->html(['data'   => ['title' => $title, 'text' => $text, 'file' => $file],
                                'errors' => $res]);
        }
        return $this->html(null);
    }

    /**
     * Action to delete a particular News
     * @throws \Exception
     */
    public function delete()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=MainPage");
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
                $this->redirect("semestralka?c=MainPage");
            }

            $actualPage = $getData['page'];
            $numElements = $getData['numElements'];

            $news->delete();
            if ($numElements > 1)
            {
                $this->redirect("semestralka?c=MainPage&page=". $actualPage);
            }
            else
            {
                $actualPage = ($actualPage == 1 ? 1 : $actualPage - 1);
                $this->redirect("semestralka?c=MainPage&page=". $actualPage);
            }
        }
        $this->redirect("semestralka?c=MainPage");
    }

    /**
     * Action to edit a news based on its ID
     * @return \App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function edit()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=MainPage");
        }

        $postData = $this->app->getRequest()->getPost();
        $getData = $this->app->getRequest()->getGet();
        if (!isset($getData['id']))
        {
            return $this->html("semestralka?c=MainPage");
        }

        $news = null;
        $errs = null;
        try
        {
            $news = News::getOne($getData['id']);
        }
        catch (KeyNotFoundException $ex)
        {
            $this->redirect("semestralka?c=MainPage");
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
                $this->redirect("semestralka?c=MainPage&page=". $_SESSION['page']);
            }
        }

        if (isset($getData['page']))
            $_SESSION['page'] = $getData['page'];

        return $this->html(['data' => $news, 'errors' => $errs]);
    }

    /**
     * Function used to validate data for News.
     * If data is valid returns null otherwise it returns array with errors.
     * @param $title
     * @param $text
     * @param $file
     * @return array|null
     */
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