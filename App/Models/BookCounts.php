<?php

namespace App\Models;

/**
 * Class BookCounts is a helper class for book count data in reservation
 * @package App\Models
 */
class BookCounts implements \JsonSerializable
{
    private $ISBN;
    private $count;

    /**
     * BookCounts constructor.
     * @param $ISBN
     * @param $count
     */
    public function __construct($ISBN = null, $count = null)
    {
        $this->ISBN = $ISBN;
        $this->count = $count;
    }

    /**
     * @return string[]
     */
    public static function getDBColumns()
    {
        return ['ISBN', 'cnt'];
    }

    /**
     * Sets the values from data
     * @param $data
     */
    public function setValues($data)
    {
        $this->ISBN = $data['ISBN'];
        $this->count = $data['cnt'];
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

    /**
     * @return mixed|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed|null $count
     */
    public function setCount( $count): void
    {
        $this->count = $count;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}