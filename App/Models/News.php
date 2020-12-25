<?php

namespace App\Models;
use App\Core\Model;
use DateTime;

class News extends Model
{
    private const MAX_TITLE_LEN = 30;
    private const MAX_FILE_SIZE = 14000000;

    protected $id;
    protected $title;
    protected $text;
    protected $picture;
    protected $creation_date;
    protected $email;

    public function __construct($title = null, $text = null, $picture = null,
                                $creation_date = null, $email = null)
    {
        $this->id = null;
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

    /**
     * Checks text of news
     * @param $text
     * @return array
     */
    static public function checkText($text)
    {
        $textErrs = [];
        if (empty($text))
        {
            $textErrs[] = 'Prosím zadajte text';
        }
        return $textErrs;
    }

    /**
     * Checks title of news
     * @param $title
     * @return array
     */
    static public function checkTitle($title)
    {
        $titleErrs = [];
        if (empty($title))
        {
            $titleErrs[] = 'Prosím zadajte nadpis';
        }
        else
        {
            if (strlen($title) > self::MAX_TITLE_LEN)
            {
                $titleErrs[] = 'Nadpis môže mať maximálne 30 znakov';
            }
        }
        return $titleErrs;
    }

    /**
     * Checks uploaded image
     * @param $file
     * @return array
     */
    static public function checkFile($file)
    {
        $fileErrs = [];
        if (!isset($file['error']))
        {
            $fileErrs[] = 'Prosím nahrajte obrázok';
            return $fileErrs;
        }
        else
        {
            if (is_array($file['error']))
            {
                $fileErrs[] = 'Nahrajte iba jeden súbor';
                return $fileErrs;
            }

            switch ($file['error'])
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $fileErrs[] = 'Prosím nahrajte obrázok';
                    return $fileErrs;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $fileErrs[] = 'Obrázok je príliš veľký';
                    return $fileErrs;
                default:
                    throw new \RuntimeException('File upload error');
            }

            $finfo = finfo_open( FILEINFO_MIME_TYPE );
            $mtype = finfo_file( $finfo, $file['tmp_name'] );
            finfo_close( $finfo );
            if ( $mtype != ( "image/png" ) && $mtype != ( "image/jpeg" ))
            {
                $fileErrs[] = 'Obrázok musí byť .png alebo .jpeg';
                return $fileErrs;
            }

            $image_base64 = base64_encode(file_get_contents($file['tmp_name']));
            if(strlen($image_base64) > self::MAX_FILE_SIZE)
            {
                $fileErrs[] = 'Obrázok je príliš veľký';
            }
        }

        return $fileErrs;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getCreationDateFormated()
    {
        $date = new DateTime($this->creation_date);
        return $date->format('d.m.Y H:i');
    }

    // region Getters and Setters
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

    // endregion

}