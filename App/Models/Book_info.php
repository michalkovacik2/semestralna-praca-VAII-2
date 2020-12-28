<?php


namespace App\Models;
use App\Core\Model;

class Book_info extends Model
{
    protected $ISBN;
    protected $name;
    protected $genre_id;
    protected $author_name;
    protected $author_surname;
    protected $release_year;
    protected $info;
    protected $picture;
    protected $genre_name;

    /**
     * Book_info constructor.
     * @param $ISBN
     * @param $name
     * @param $genre_id
     * @param $author_name
     * @param $author_surname
     * @param $release_year
     * @param $info
     * @param $picture
     */
    public function __construct($ISBN = null, $name = null, $genre_id = null, $author_name = null, $author_surname = null, $release_year = null, $info = null, $picture = null)
    {
        $this->ISBN = $ISBN;
        $this->name = $name;
        $this->genre_id = $genre_id;
        $this->author_name = $author_name;
        $this->author_surname = $author_surname;
        $this->release_year = $release_year;
        $this->info = $info;
        $this->picture = $picture;
    }

    static public function setDbColumns()
    {
        return ['ISBN', 'name', 'genre_id', 'author_name', 'author_surname', 'release_year', 'info',
                'picture'];
    }

    static public function getAllColumns()
    {
        return ['ISBN', 'name', 'genre_id', 'author_name', 'author_surname', 'release_year', 'info',
            'picture', 'genre_name'];
    }

    public function setValues($data)
    {
        $this->ISBN = $data['ISBN'];
        $this->name = $data['name'];
        $this->genre_id = $data['genre_id'];
        $this->author_name = $data['author_name'];
        $this->author_surname = $data['author_surname'];
        $this->release_year = $data['release_year'];
        $this->info = $data['info'];
        $this->picture = $data['picture'];
        $this->genre_name = $data['genre_name'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'ISBN';
    }

    static public function setTableName()
    {
        return 'book_info';
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
    public function getGenreId()
    {
        return $this->genre_id;
    }

    /**
     * @param mixed|null $genre_id
     */
    public function setGenreId( $genre_id): void
    {
        $this->genre_id = $genre_id;
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
    public function setAuthorName( $author_name): void
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
    public function setAuthorSurname( $author_surname): void
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
    public function setReleaseYear( $release_year): void
    {
        $this->release_year = $release_year;
    }

    /**
     * @return mixed|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed|null $info
     */
    public function setInfo( $info): void
    {
        $this->info = $info;
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
    public function setPicture( $picture): void
    {
        $this->picture = $picture;
    }
}