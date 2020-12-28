<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\ComplexQuery;
use App\Core\KeyNotFoundException;
use App\Models\Book;
use App\Models\Book_info;
use App\Models\GenreCounts;
use App\Models\Reservation;

class ReserveController extends AControllerBase
{
    private const BOOKS_PER_PAGE = 5;

    public function index()
    {
        return $this->html(null);
    }

    public function genres()
    {
        return $this->json($this->generateGenres());
    }

    public function books()
    {
        $getData = $this->app->getRequest()->getGet();
        $whereCondition = null;
        if (isset($getData['filter']))
        {
            $whereCondition = $getData['filter'];
        }

        $page = 1;
        if (isset($getData['page']))
        {
            $page = $getData['page'];
        }
        $page--;

        $like = null;
        if (isset($getData['like']))
        {
            $like = $getData['like'];
            $like =  addcslashes($like, '%_');
        }

        $books = ComplexQuery::getBooks($whereCondition, $like,$page*self::BOOKS_PER_PAGE, self::BOOKS_PER_PAGE);

        array_unshift($books, ['ALL' => ComplexQuery::getBooksCount($whereCondition, $like)]);
        return $this->json($books);
    }

    public function countBook()
    {
        $getData = $this->app->getRequest()->getGet();
        $whereCondition = null;
        if (isset($getData['filter']))
        {
            $whereCondition = $getData['filter'];
        }

        $bookCount = ComplexQuery::getAvailableBooksCount($whereCondition);
        return $this->json($bookCount);
    }

    public function reserveBook()
    {
        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody);

        if (!isset($data->ISBN))
        {
            return $this->json(['Error' => 'Musíte zadať ISBN']);
        }

        if (!$this->app->getAuth()->isLogged())
        {
            return $this->json(['Error' => 'Na rezervovanie knihy musíte byť prihlásený']);
        }

        try
        {
            $book = Book_info::getOne($data->ISBN);
        }
        catch(KeyNotFoundException $e)
        {
            return $this->json(['Error' => 'Kniha so zadaným ISBN neexistuje']);
        }

        if (ComplexQuery::getNumberOfReservationsByUserForBook($data->ISBN, $this->app->getAuth()->getLoggedUser()->getEmail()) != 0)
        {
            return $this->json(['Error' => 'Môžete si rezervovať iba jednu rovnakú knihu']);
        }

        /** @var $freeBooks Book[] */
        $freeBooks = ComplexQuery::getAvailableBooks($data->ISBN);
        $r = new Reservation(date('Y-m-d'), null, null, $freeBooks[0]->getBookId(), $this->app->getAuth()->getLoggedUser()->getEmail());
        $r->save();
        return $this->json(['Error' => '']);
    }

    private function generateGenres()
    {
        $genres = ComplexQuery::getGenresCount();
        $sum = 0;
        foreach ($genres as $genre)
        {
            /** @var $genre GenreCounts */
            $sum += $genre->getCount();
        }
        array_unshift($genres, new GenreCounts('Všetky', strval($sum)));
        return $genres;
    }
}