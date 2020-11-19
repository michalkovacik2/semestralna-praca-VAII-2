<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Models\News;

class MainPageController extends AControllerBase
{
    private const NEWS_PER_PAGE = 4;

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
        header('Location: semestralka?c=MainPage');
        die();
    }
}