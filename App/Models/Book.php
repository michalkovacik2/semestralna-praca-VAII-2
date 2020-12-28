<?php

namespace App\Models;
use App\Core\Model;

class Book extends Model
{
    protected $book_id;
    protected $ISBN;

    /**
     * Book constructor.
     * @param $book_id
     * @param $ISBN
     */
    public function __construct($ISBN = null)
    {
        $this->book_id = null;
        $this->ISBN = $ISBN;
    }



    static public function setDbColumns()
    {
        return ['book_id', 'ISBN'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'book_id';
    }

    static public function setTableName()
    {
        return 'book';
    }

    static public function getAllColumns()
    {
        return ['book_id', 'ISBN'];
    }

    public function setValues($data)
    {
        $this->book_id = $data['book_id'];
        $this->ISBN = $data['ISBN'];
    }

    /**
     * @return null
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @return mixed|null
     */
    public function getISBN()
    {
        return $this->ISBN;
    }

    /**
     * @param mixed|null $ISBN
     */
    public function setISBN( $ISBN): void
    {
        $this->ISBN = $ISBN;
    }
}