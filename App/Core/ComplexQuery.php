<?php

namespace App\Core;

use App\Core\DB\Connection;
use App\Models\Book;
use App\Models\Book_info;
use App\Models\BookCounts;
use App\Models\BookReservationsPending;
use App\Models\Genre;
use App\Models\GenreCounts;
use App\Models\History;
use JsonSerializable;
use PDO;
use PDOException;

/**
 * Class ComplexQuery is used to get data from more than one table
 * @package App\Core
 */
class ComplexQuery
{
    private static $connection = null;

    /**
     * Gets DB connection for other model methods
     * @return null
     * @throws \Exception
     */
    private static function connect()
    {
        self::$connection = Connection::connect();
    }

    /**
     * Gets names of genres and number of books that belongs to that genre
     * ordered by number of books in each category, the largest number is first
     * SQL query:
     * select g.name, COUNT(ISBN) as cnt, g.genre_id from book_info
     *      right join genre g on book_info.genre_id = g.genre_id
     *          group by book_info.genre_id
     *              order by cnt DESC;
     *
     * @return GenreCounts[]
     * @throws \Exception
     */
    static public function getGenresCount()
    {
        self::connect();
        try {
            $sql = "select g.name, COUNT(ISBN) as cnt, g.genre_id from book_info right join genre g on book_info.genre_id = g.genre_id group by book_info.genre_id order by cnt DESC";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new GenreCounts();
                $data = array_fill_keys(GenreCounts::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets user history of reserved books, the newest reservations are first
     * SQL Query:
     * SELECT * from reservation
     *      join book b on reservation.book_id = b.book_id
     *      join book_info bi on b.ISBN = bi.ISBN
     *          where email = :PK
     *              order by request_date DESC
     *                  LIMIT :limit, :len
     *
     * @param int $from - from which index we want the data used in LIMIT
     * @param int $len - How many rows do we want also used in LIMIT
     * @param string $email - for which user
     * @return History[]
     * @throws \Exception
     */
    static public function getUserHistory(int $from, int $len, string $email)
    {
        self::connect();
        try {
            $columns = implode(',', History::getDBColumns());
            $sql = "SELECT " . $columns . " from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN where email = :PK order by request_date DESC LIMIT :limit, :len";

            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':limit', (int) $from, PDO::PARAM_INT);
            $stmt->bindValue(':len', (int) $len, PDO::PARAM_INT);
            $stmt->bindValue(':PK', (string) $email, PDO::PARAM_STR);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new History();
                $data = array_fill_keys(History::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets the total number of rows of history of user
     * SQL query:
     * SELECT COUNT(reservation_id) from reservation
     *      join book b on reservation.book_id = b.book_id
     *      join book_info bi on b.ISBN = bi.ISBN
     *          where email = :PK;
     *
     * @param string $email - for which user
     * @return int
     * @throws \Exception
     */
    static public function getUserHistoryCount(string $email)
    {
        self::connect();
        try {
            $sql = "SELECT COUNT(reservation_id) from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN where email = :PK";

            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':PK', (string) $email, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            return intval($res[0]);
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Get all books that meet certain criteria like genre, name and the output is limited
     * by how much data do we want. It is ordered by the name of book alphabetically.
     * SQL query:
     * select [columns] from book_info
     *      join genre g on book_info.genre_id = g.genre_id
     *          WHERE book_info.genre_id = :genreID AND book_info.name LIKE :likeStmt
     *              ORDER BY book_info.name
     *                  LIMIT :startI, :howMuch;
     *
     * @param $genreID - books from what genre we want
     * @param $likeStatement - books name contains this word
     * @param int $start - from which index do we want the data used in LIMIT
     * @param int $howMuch - how much data do we want used in LIMIT
     * @return Book_info[]
     * @throws \Exception
     */
    static public function getBooks($genreID, $likeStatement ,int $start, int $howMuch)
    {
        self::connect();
        try {
            $whereVal = "";
            if (!is_null($genreID) && !(is_null($likeStatement)))
            {
                $whereVal = " WHERE book_info.genre_id = :genreID AND book_info.name LIKE :likeStmt";
            }
            else if (!is_null($genreID) && is_null($likeStatement))
            {
                $whereVal = " WHERE book_info.genre_id = :genreID";
            }
            else if (!is_null($likeStatement) && is_null($genreID))
            {
                $whereVal = " WHERE book_info.name LIKE :likeStmt";
            }
            $sql = "select ISBN, book_info.name, book_info.genre_id, author_name, author_surname, release_year, info, picture, g.name as genre_name from book_info join genre g on book_info.genre_id = g.genre_id" . $whereVal . " ORDER BY book_info.name LIMIT :startI, :howMuch";

            $stmt = self::$connection->prepare($sql);
            if (!is_null($genreID))
                $stmt->bindValue(':genreID', (int) $genreID, PDO::PARAM_INT);
            if (!is_null($likeStatement))
                $stmt->bindValue(':likeStmt', "%$likeStatement%");

            $stmt->bindValue(':startI', (int) $start, PDO::PARAM_INT);
            $stmt->bindValue(':howMuch', (int) $howMuch, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new Book_info();
                $data = array_fill_keys(Book_info::getAllColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets the total books count of all books without the limit
     * SQL query:
     * select COUNT(*) from book_info
     *      join genre g on book_info.genre_id = g.genre_id
     *          WHERE book_info.genre_id = :genreID AND book_info.name LIKE :likeStmt;
     *
     * @param $genreID - books from what genre we want
     * @param $likeStatement - books name contains this word
     * @return int
     * @throws \Exception
     */
    static public function getBooksCount($genreID, $likeStatement)
    {
        self::connect();
        try {
            $whereVal = "";
            if (!is_null($genreID) && !(is_null($likeStatement)))
            {
                $whereVal = " WHERE book_info.genre_id = :genreID AND book_info.name LIKE :likeStmt";
            }
            else if (!is_null($genreID))
            {
                $whereVal = " WHERE book_info.genre_id = :genreID";
            }
            else if (!is_null($likeStatement))
            {
                $whereVal = " WHERE book_info.name LIKE :likeStmt";
            }
            $sql = "select COUNT(*) from book_info join genre g on book_info.genre_id = g.genre_id" . $whereVal;

            $stmt = self::$connection->prepare($sql);
            if (!is_null($genreID))
                $stmt->bindValue(':genreID', (int) $genreID, PDO::PARAM_INT);

            if (!is_null($likeStatement))
                $stmt->bindValue(':likeStmt', "%$likeStatement%");

            $stmt->execute();
            $res = $stmt->fetch();
            return intval($res[0]);
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Returns the number of pending reservations made by particular user for this book.
     * SQL Query:
     * select COUNT(*) from reservation
     *      join book b on reservation.book_id = b.book_id
     *          where email = :emailIn and return_day IS NULL and ISBN = :ISBNIn
     *
     * @param int $ISBN - book in which we are interested
     * @param string $email - users email
     * @return int
     * @throws \Exception
     */
    static public function getNumberOfReservationsByUserForBook(int $ISBN, string $email)
    {
        self::connect();
        try {
            $sql = "select COUNT(*) from reservation join book b on reservation.book_id = b.book_id where email = :emailIn and return_day IS NULL and ISBN = :ISBNIn";

            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':emailIn', $email, PDO::PARAM_STR);
            $stmt->bindValue(':ISBNIn', (int) $ISBN, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            return intval($res[0]);
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets the number of available books which can be reserved (their return day is not null)
     * SQL Query:
     * select book.ISBN, COUNT(book.ISBN) as cnt from book
     *      join book_info bi on book.ISBN = bi.ISBN
     *          where bi.genre_id = :genreID and book_id not in
     *              (select book_id from reservation where return_day is null)
     *                  group by book.ISBN;
     *
     * @param $genreID - genre of books we want
     * @return BookCounts[]
     * @throws \Exception
     */
    static public function getAvailableBooksCount($genreID)
    {
        self::connect();
        try {
            $whereVal = "";
            if (!is_null($genreID))
            {
                $whereVal = " bi.genre_id = :genreID and";
            }
            $sql = "select book.ISBN, COUNT(book.ISBN) as cnt from book join book_info bi on book.ISBN = bi.ISBN where" . $whereVal . " book_id not in (select book_id from reservation where return_day is null) group by book.ISBN";

            $stmt = self::$connection->prepare($sql);
            if (!is_null($genreID))
                $stmt->bindValue(':genreID', (int) $genreID, PDO::PARAM_INT);

            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new BookCounts();
                $data = array_fill_keys(BookCounts::getDBColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets books that can be reserved by user by books ISBN
     * SQL Query:
     * select book_id, book.ISBN from book
     *      where book.ISBN = :ISBNIn and book_id not in
     *          (select book_id from reservation where return_day is null)
     *
     * @param int $ISBN - books of which book do we want
     * @return Book[]
     * @throws \Exception
     */
    static public function getAvailableBooks(int $ISBN)
    {
        self::connect();
        try {
            $sql = "select book_id, book.ISBN from book where book.ISBN = :ISBNIn and book_id not in (select book_id from reservation where return_day is null)";
            $stmt = self::$connection->prepare($sql);

            $stmt->bindValue(':ISBNIn', (int) $ISBN, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new Book();
                $data = array_fill_keys(Book::getAllColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets reservations of users reservations that are not returned are first
     * SQL query:
     * select reservation_id, b.ISBN, name, b.book_id, email, request_date, reserve_day, return_day from reservation
     *      join book b on reservation.book_id = b.book_id
     *      join book_info bi on b.ISBN = bi.ISBN
     *          where email like :likeStmt
     *              ORDER BY (CASE WHEN return_day IS NULL THEN 0 ELSE 1 END), request_date DESC
     *                  LIMIT :startI, :howMuch
     *
     * @param $like - for searching purposes
     * @param int $start - from which index to start used in LIMIT
     * @param int $howMuch - how much data do we want used also in LIMIT
     * @return BookReservationsPending[]
     * @throws \Exception
     */
    static public function getUserReservations($like, int $start, int $howMuch)
    {
        self::connect();
        try {
            $whereStm = "";
            if (!is_null($like))
            {
               $whereStm = "where email like :likeStmt";
            }

            $sql = "select reservation_id, b.ISBN, name, b.book_id, email, request_date, reserve_day, return_day from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN " . $whereStm . " ORDER BY (CASE WHEN return_day IS NULL THEN 0 ELSE 1 END), request_date DESC LIMIT :startI, :howMuch";
            $stmt = self::$connection->prepare($sql);

            if (!is_null($like))
                $stmt->bindValue(':likeStmt', "$like%");


            $stmt->bindValue(':startI', (int) $start, PDO::PARAM_INT);
            $stmt->bindValue(':howMuch', (int) $howMuch, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new BookReservationsPending();
                $data = array_fill_keys(BookReservationsPending::getDBColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets the count of all user reservation that meets the criteria
     * SQL query:
     * select COUNT(reservation_id) from reservation
     *      join book b on reservation.book_id = b.book_id
     *      join book_info bi on b.ISBN = bi.ISBN
     *          where email like :likeStmt;
     *
     * @param $like - for searching purposes
     * @return int
     * @throws \Exception
     */
    static public function getUserReservationsCount($like)
    {
        self::connect();
        try {
            $whereStm = "";
            if (!is_null($like))
            {
                $whereStm = "where email like :likeStmt";
            }

            $sql = "select COUNT(reservation_id) from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN " . $whereStm;
            $stmt = self::$connection->prepare($sql);

            if (!is_null($like))
                $stmt->bindValue(':likeStmt', "$like%");

            $stmt->execute();
            $res = $stmt->fetch();
            return intval($res[0]);
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }
}