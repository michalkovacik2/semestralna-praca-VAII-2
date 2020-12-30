<?php

namespace App\Models;
use App\Core\Model;

/**
 * Class Genre represents database entity Genre
 * @package App\Models
 */
class Genre extends Model
{
    private const MAX_SIZE_NAME = 30;

    protected $genre_id;
    protected $name;

    /**
     * Genre constructor.
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

    /** Checks if genre name is valid
     * @param $name
     * @return array - errors found, empty if none are found
     */
    static public function checkName($name)
    {
        $nameErrs = [];
        if (empty($name))
        {
            $nameErrs[] = "Prosím zadajte názov žánru";
        }
        else
        {
            if (strlen($name) > self::MAX_SIZE_NAME)
                $nameErrs[] = "Názov nesmie byť dlhší ako " . self::MAX_SIZE_NAME;
        }
        return $nameErrs;
    }

    //region Getters and setters

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

    //endregion
}