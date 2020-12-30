<?php

namespace App\Models;
use DateTime;

/**
 * Class History is a helper class for user history on profil page
 * @package App\Models
 */
class History
{
    private $name;
    private $author_name;
    private $author_surname;
    private $release_year;
    private $request_date;
    private $reserve_day;
    private $return_day;
    private $picture;

    /**
     * History constructor.
     * @param $name
     * @param $author_name
     * @param $author_surname
     * @param $release_year
     * @param $request_date
     * @param $reserve_day
     * @param $return_day
     * @param $picture
     */
    public function __construct($name = null, $author_name = null, $author_surname = null, $release_year = null, $request_date = null, $reserve_day = null, $return_day = null, $picture = null)
    {
        $this->name = $name;
        $this->author_name = $author_name;
        $this->author_surname = $author_surname;
        $this->release_year = $release_year;
        $this->request_date = $request_date;
        $this->reserve_day = $reserve_day;
        $this->return_day = $return_day;
        $this->picture = $picture;
    }

    /**
     * @return string[]
     */
    public static function getDBColumns()
    {
        return ['name', 'author_name', 'author_surname', 'release_year', 'request_date',
                'reserve_day', 'return_day', 'picture'];
    }

    /**
     * Sets the values from data
     * @param $data
     */
    public function setValues($data)
    {
        $this->name = $data['name'];
        $this->author_name = $data['author_name'];
        $this->author_surname = $data['author_surname'];
        $this->release_year = $data['release_year'];
        $this->request_date = $data['request_date'];
        $this->reserve_day = $data['reserve_day'];
        $this->return_day = $data['return_day'];
        $this->picture = $data['picture'];
    }

    // region Getters and setters

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

    /**
     * @return mixed|null
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * @param mixed|null $author_name
     */
    public function setAuthorName($author_name): void
    {
        $this->author_name = $author_name;
    }

    /**
     * @return mixed|null
     */
    public function getAuthorSurname()
    {
        return $this->author_surname;
    }

    /**
     * @param mixed|null $author_surname
     */
    public function setAuthorSurname($author_surname): void
    {
        $this->author_surname = $author_surname;
    }

    /**
     * @return mixed|null
     */
    public function getReleaseYear()
    {
        return $this->release_year;
    }

    /**
     * @param mixed|null $release_year
     */
    public function setReleaseYear($release_year): void
    {
        $this->release_year = $release_year;
    }

    /**
     * @return mixed|null
     */
    public function getRequestDate()
    {
        return $this->request_date;
    }

    /**
     * @return mixed|null
     */
    public function getRequestDateFormatted()
    {
        $date = new DateTime($this->request_date);
        return $date->format('d.m.Y');
    }

    /**
     * @param mixed|null $request_date
     */
    public function setRequestDate($request_date): void
    {
        $this->request_date = $request_date;
    }

    /**
     * @return mixed|null
     */
    public function getReserveDay()
    {
        return $this->reserve_day;
    }

    /**
     * @return mixed|null
     */
    public function getReserveDayFormatted()
    {
        if (is_null($this->reserve_day))
        {
            return 'Nevyzdvihnuté';
        }
        $date = new DateTime($this->reserve_day);
        return $date->format('d.m.Y');
    }

    /**
     * @param mixed|null $reserve_day
     */
    public function setReserveDay($reserve_day): void
    {
        $this->reserve_day = $reserve_day;
    }

    /**
     * @return mixed|null
     */
    public function getReturnDay()
    {
        return $this->return_day;
    }

    /**
     * @return mixed|null
     */
    public function getReturnDayFormatted()
    {
        if (is_null($this->reserve_day))
        {
            return 'Nevrátené';
        }
        $date = new DateTime($this->return_day);
        return $date->format('d.m.Y');
    }

    /**
     * @param mixed|null $return_day
     */
    public function setReturnDay($return_day): void
    {
        $this->return_day = $return_day;
    }

    /**
     * @return mixed|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed|null $picture
     */
    public function setPicture($picture): void
    {
        $this->picture = $picture;
    }

    //endregion

}