<?php


namespace App\Models;
use App\Core\KeyNotFoundException;
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

    private const MAX_SIZE_NAME = 40;
    private const ISBN_LENGTH = 13;
    private const MAX_AUTHOR_NAME = 30;
    private const MAX_AUTHOR_SURNAME = 50;
    private const MAX_YEAR_LENGTH = 4;

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

    static public function checkName($name)
    {
        $nameErrs = [];
        if (empty($name))
        {
            $nameErrs[] = "Prosím zadajte názov knihy";
        }
        else
        {
            if (strlen($name) > self::MAX_SIZE_NAME)
                $nameErrs[] = "Meno nesmie byť dlhšie ako " . self::MAX_SIZE_NAME;
        }
        return $nameErrs;
    }

    static public function checkISBN($ISBN)
    {
        $ISBNerrs = [];
        if (empty($ISBN))
        {
            $ISBNerrs[] = "Prosím zadajte ISBN";
        }
        else
        {
            $ISBN = str_replace(' ', '', $ISBN);
            $ISBN = str_replace('-', '', $ISBN);
            if (preg_match('/[^0-9]/', $ISBN))
            {
                $ISBNerrs[] = "ISBN môže pozostávať iba z číslic";
                return $ISBNerrs;
            }

            try
            {
                Book_info::getOne($ISBN);
                $ISBNerrs[] = "Kniha so zadanym ISBN uz existuje";
            }
            catch (KeyNotFoundException $e)
            {

            }

            if (substr($ISBN, 0, 3) != "978" && substr($ISBN, 0, 3) != "979")
                $ISBNerrs[] = "ISBN musí začínať na 978 alebo 979";

            if (strlen($ISBN) != self::ISBN_LENGTH)
            {
                $ISBNerrs[] = "ISBN musí obsahvať ". self::ISBN_LENGTH . " číslic";
                return $ISBNerrs;
            }

            $even = 0;
            $odd = 0;
            for ($i = 0; $i < self::ISBN_LENGTH - 1; $i++)
            {
                if (($i == 0) || (($i % 2) == 0))
                    $even += intval($ISBN[$i]);
                else
                    $odd += intval($ISBN[$i]);
            }

            $odd *= 3;
            $total_sum = $even + $odd;
            $next_ten = (ceil($total_sum / 10)) * 10;
            $controlDigit = $next_ten - $total_sum;
            if ($controlDigit != intval($ISBN[12]))
            {
                $ISBNerrs[] = "Toto nie je valídny ISBN";
            }

        }
        return $ISBNerrs;
    }

    static public function checkAuthorName($author_name)
    {
        $nameErrs = [];
        if (empty($author_name))
        {
            $nameErrs[] = "Prosím zadajte meno autora";
        }
        else
        {
            if (strlen($author_name) > self::MAX_AUTHOR_NAME)
                $nameErrs[] = "Meno autora nesmie byť dlhšie ako " . self::MAX_AUTHOR_NAME;
        }
        return $nameErrs;
    }

    static public function checkAuthorSurname($author_surname)
    {
        $surnameErrs = [];
        if (empty($author_surname))
        {
            $surnameErrs[] = "Prosím zadajte priezvisko autora";
        }
        else
        {
            if (strlen($author_surname) > self::MAX_AUTHOR_SURNAME)
                $surnameErrs[] = "Priezvisko autora nesmie byť dlhšie ako " . self::MAX_AUTHOR_SURNAME;
        }
        return $surnameErrs;
    }

    static public function checkReleaseYear($year)
    {
        $yearErrs = [];
        if (empty($year))
        {
            $yearErrs[] = "Prosím zadajte rok vydania";
        }
        else
        {
            if (preg_match('/[^0-9]/', $year))
                $yearErrs[] = "Rok nesmie obsahovať písmená";

            if (strlen($year) > self::MAX_YEAR_LENGTH)
                $yearErrs[] = "Rok nesmie mať viac ako 4 číslice";

            $currentYear = date("Y");
            if (intval($year) > intval($currentYear))
            {
                $yearErrs[] = "Rok je v budúcnosti";
            }
        }
        return $yearErrs;
    }

    static public function checkInfo($info)
    {
        $textErrs = [];
        if (empty($info))
        {
            $textErrs[] = 'Popis nesmie byť prázdny';
        }
        return $textErrs;
    }

    static public function checkGenreID($genre)
    {
        $genreErrs = [];
        try
        {
            $genre = Genre::getOne($genre);
        }
        catch(KeyNotFoundException $e)
        {
            $genreErrs[] = "Zadaný žáner neexistuje";
        }

        return $genreErrs;
    }

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
        }

        return $fileErrs;
    }

    // region Getters and setters
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

    // endregion
}