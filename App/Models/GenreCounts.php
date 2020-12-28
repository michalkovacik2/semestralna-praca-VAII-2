<?php

namespace App\Models;

class GenreCounts implements \JsonSerializable
{
    private $genre_id;
    private $name;
    private $count;

    /**
     * GenreCounts constructor.
     * @param $name
     * @param $count
     */
    public function __construct($name = null, $count = null, $genre_id = null)
    {
        $this->genre_id = $genre_id;
        $this->name = $name;
        $this->count = $count;
    }

    /**
     * @return string[]
     */
    public static function getDBColumns()
    {
        return ['name', 'cnt', 'genre_id'];
    }

    public function setValues($data)
    {
        $this->name = $data['name'];
        $this->count = $data['cnt'];
        $this->genre_id = $data['genre_id'];
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
    public function setName( $name): void
    {
        $this->name = $name;
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
    public function setCount($count): void
    {
        $this->count = $count;
    }
    /**
     * @return mixed|null
     */
    public function getGenreId()
    {
        return $this->genre_id;
    }

    /**
     * @param mixed|null $genre_id
     */
    public function setGenreId($genre_id): void
    {
        $this->genre_id = $genre_id;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}