<?php

namespace App\Models;
use App\Core\Model;

class Genre extends Model
{
    protected $genre_id;
    protected $name;

    /**
     * Genre constructor.
     * @param $genre_id
     * @param $name
     */
    public function __construct($name = null)
    {
        $this->genre_id = null;
        $this->name = $name;
    }

    static public function setDbColumns()
    {
        return ['genre_id', 'name'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'genre_id';
    }

    static public function setTableName()
    {
        return 'genre';
    }


    /**
     * @return null
     */
    public function getGenreId()
    {
        return $this->genre_id;
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed|null $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}