<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\ComplexQuery;
use App\Core\KeyNotFoundException;
use App\Models\Book;
use App\Models\Book_info;
use App\Models\Genre;
use App\Models\GenreCounts;
use App\Models\Reservation;

class ReserveController extends AControllerBase
{
    private const BOOKS_PER_PAGE = 5;

    public function index()
    {
        return $this->html(null);
    }

    public function addBook()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=Reserve");
        }

        $postData = $this->app->getRequest()->getPost();
        if (empty($postData))
        {
            $genres = Genre::getAll();
            $data['genres'] = $genres;
            return $this->html($data);
        }

        $filesData = $this->app->getRequest()->getFiles();
        $file = $filesData['file'];
        $name = $postData['name'];
        $ISBN = $postData['ISBN'];
        $author_name = $postData['author_name'];
        $author_surname = $postData['author_surname'];
        $release_year = $postData['release_year'];
        $info = $postData['info'];
        $genre_id = $postData['genre'];
        $amount = $postData['amount'];

        $res = $this->validate($ISBN, $name, $author_name, $author_surname, $release_year, $info, $genre_id, $file, $amount);
        if(is_null($res))
        {
            $ISBN = str_replace(' ', '', $ISBN);
            $ISBN = str_replace('-', '', $ISBN);
            $ISBN = intval($ISBN);
            $name = htmlspecialchars($name);
            $author_name = htmlspecialchars($author_name);
            $author_surname = htmlspecialchars($author_surname);
            $release_year = intval($release_year);
            $info = htmlspecialchars($info);
            $genre_id = intval($genre_id);
            $image = file_get_contents($file['tmp_name']);
            $picture = uniqid('book');
            $path = './img/books/'. $picture;
            file_put_contents($path, $image);

            $bi = new Book_info($ISBN, $name, $genre_id, $author_name, $author_surname, $release_year, $info, $picture);
            $bi->insert();

            $amount = intval($amount);
            $b = new Book($ISBN);
            for ($i = 0; $i < $amount; $i++)
                $b->save();

            $this->redirect("semestralka?c=Reserve");
        }

        $genres = Genre::getAll();
        return $this->html(['data'   => ['name' => $name, 'ISBN' => $ISBN, 'file' => $file, 'author_name' => $author_name,
                                         'author_surname' => $author_surname, 'release_year' => $release_year, 'info' => $info, 'genre' => $genre_id, 'amount' => $amount]
                                          , 'errors' => $res, 'genres' => $genres]);
    }

    public function editBook()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges()) {
            $this->redirect("semestralka?c=Reserve");
        }

        $postData = $this->app->getRequest()->getPost();
        $getData = $this->app->getRequest()->getGet();
        if (!isset($getData['ISBN'])) {
            $this->redirect("semestralka?c=Reserve");
        }

        /** @var $bookInfo Book_info */
        $bookInfo = null;
        $errs = null;
        try {
            $bookInfo = Book_info::getOne($getData['ISBN']);
        } catch (KeyNotFoundException $ex) {
            $this->redirect("semestralka?c=Reserve");
        }

        $name = $bookInfo->getName();
        $author_name = $bookInfo->getAuthorName();
        $author_surname = $bookInfo->getAuthorSurname();
        $release_year = $bookInfo->getReleaseYear();
        $info = $bookInfo->getInfo();
        $genre_id = $bookInfo->getGenreId();
        $file = $bookInfo->getPicture();

        if (isset($postData['name']) || isset($postData['author_name']) || isset($postData['author_surname']) || isset($postData['release_year']) ||
            isset($postData['info']) || isset($postData['genre']) || isset($postData['file'])) {
            $nameErrs = Book_info::checkName($postData['name']);
            $author_nameErrs = Book_info::checkAuthorName($postData['author_name']);
            $author_surnameErrs = Book_info::checkAuthorSurname($postData['author_surname']);
            $release_yearErrs = Book_info::checkReleaseYear($postData['release_year']);
            $infoErrs = Book_info::checkInfo($postData['info']);
            $genre_idErrs = Book_info::checkGenreID($postData['genre']);
            $fileErrs = [];

            $FILE = $this->app->getRequest()->getFiles();
            if (isset($FILE['file']) && $FILE['file']['size'] != 0)
            {
                $fileErrs = Book_info::checkFile($FILE['file']);
            }

            $name = $postData['name'];
            $author_name = $postData['author_name'];
            $author_surname = $postData['author_surname'];
            $release_year = $postData['release_year'];
            $info = $postData['info'];
            $genre_id = $postData['genre'];

            if (count($nameErrs) > 0 || count($author_nameErrs) > 0 || count($author_surnameErrs) > 0 ||
                count($release_yearErrs) > 0 || count($infoErrs) > 0 || count($genre_idErrs) > 0 || count($fileErrs) > 0) {
                $errs = ['name' => $nameErrs, 'author_name' => $author_nameErrs, 'author_surname' => $author_surnameErrs,
                    'release_year' => $release_yearErrs, 'info' => $infoErrs, 'genre' => $genre_idErrs, 'file' => $fileErrs];
            }
            else
            {
                $name = htmlspecialchars($name);
                $author_name = htmlspecialchars($author_name);
                $author_surname = htmlspecialchars($author_surname);
                $release_year = intval($release_year);
                $info = htmlspecialchars($info);
                $genre_id = intval($genre_id);
                if (isset($FILE['file']) && $FILE['file']['size'] != 0)
                {
                    $image = file_get_contents($FILE['file']['tmp_name']);
                    $path = './img/books/'. $bookInfo->getPicture();
                    unlink($path);
                    $uid = uniqid('book');
                    $path = './img/books/'. $uid;
                    file_put_contents($path, $image);
                    $bookInfo->setPicture($uid);
                }
                $bookInfo->setName($name);
                $bookInfo->setAuthorName($author_name);
                $bookInfo->setAuthorSurname($author_surname);
                $bookInfo->setReleaseYear($release_year);
                $bookInfo->setInfo($info);
                $bookInfo->setGenreId($genre_id);


                $bookInfo->update();
                $this->redirect("semestralka?c=Reserve");
            }
        }

        $genres = Genre::getAll();
        return $this->html(['data'   => ['ISBN' => $getData['ISBN'], 'name' => $name, 'file' => $file, 'author_name' => $author_name,
            'author_surname' => $author_surname, 'release_year' => $release_year, 'info' => $info, 'genre' => $genre_id]
            , 'errors' => $errs, 'genres' => $genres]);
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

        $adminPriv = $this->app->getAuth()->isLogged() && $this->app->getAuth()->hasPrivileges();
        array_unshift($books, ['ALL' => ComplexQuery::getBooksCount($whereCondition, $like), 'admin' => $adminPriv]);
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
            Book_info::getOne($data->ISBN);
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

    private function validate($ISBN, $name, $author_name, $author_surname, $release_year, $info, $genre_id, $file, $amount)
    {
        $nameErrs = Book_info::checkName($name);
        $ISBNErrs = Book_info::checkISBN($ISBN);
        $author_nameErrs = Book_info::checkAuthorName($author_name);
        $author_surnameErrs = Book_info::checkAuthorSurname($author_surname);
        $release_yearErrs = Book_info::checkReleaseYear($release_year);
        $infoErrs = Book_info::checkInfo($info);
        $genre_idErrs = Book_info::checkGenreID($genre_id);
        $fileErrs = Book_info::checkFile($file);
        $amountErrs = [];

        if ($amount <= 0)
        {
            $amountErrs = "Množstvo kníh nemôže byť menej ako 1";
        }

        if (count($nameErrs) > 0 || count($ISBNErrs) > 0 || count($author_nameErrs) > 0 || count($author_surnameErrs) > 0 ||
            count($release_yearErrs) > 0 || count($infoErrs) > 0 || count($genre_idErrs) > 0 || count($fileErrs) > 0 || count($amountErrs) > 0)
        {
            return ['name' => $nameErrs, 'ISBN' => $ISBNErrs, 'author_name' => $author_nameErrs, 'author_surname' => $author_surnameErrs,
                    'release_year' => $release_yearErrs, 'info' => $infoErrs, 'genre' => $genre_idErrs, 'file' => $fileErrs, 'amount' => $amountErrs];
        }

        return null;
    }
}