<?php

namespace App\Models;
use App\Core\Model;

class News extends Model
{
    protected $id;
    protected $title;
    protected $text;
    protected $picture;
    protected $creation_date;
    protected $email;

    public function __construct($id = null, $title = null, $text = null, $picture = null,
                                $creation_date = null, $email = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->picture = $picture;
        $this->creation_date = $creation_date;
        $this->email = $email;
    }

    static public function setDbColumns()
    {
        return ['id', 'title', 'text', 'picture', 'creation_date', 'email'];
    }

    static public function setPrimaryKeyColumnName()
    {
       return 'id';
    }

    static public function setTableName()
    {
        return 'news';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function getCreationDate()
    {
        return $this->creation_date;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


}