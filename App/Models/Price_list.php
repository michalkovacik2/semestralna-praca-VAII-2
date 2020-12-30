<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Price_list represents database entity price_list
 * @package App\Models
 */
class Price_list extends Model
{
    private const MAX_SIZE_NAME = 50;
    private const MAX_SIZE_BEFORE_POINT = 10;
    private const MAX_SIZE_AFTER_POINT = 2;

    protected $id;
    protected $name;
    protected $price;

    public function __construct($name = null, $price = null)
    {
        $this->id = null;
        $this->name = $name;
        $this->price = $price;
    }

    static public function setDbColumns()
    {
        return ['id', 'name', 'price'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'id';
    }

    static public function setTableName()
    {
        return 'Price_list';
    }

    /**
     * Checks if name is valid
     * @param $name
     * @return array - errors found, empty if none are found
     */
    static public function checkName($name)
    {
        $nameErrs = [];
        if (empty($name))
        {
            $nameErrs[] = "Prosím zadajte názov";
        }
        else
        {
            if (strlen($name) > self::MAX_SIZE_NAME)
                $nameErrs[] = "Nazov nesmie byť dlhší ako " . self::MAX_SIZE_NAME;
        }
        return $nameErrs;
    }

    /**
     * Checks if price is valid
     * @param $price
     * @return array - errors found, empty if none are found
     */
    static public function checkPrice($price)
    {
        $priceErrs = [];
        if (empty($price))
        {
            $priceErrs[] = "Prosím zadajte cenu";
        }
        else
        {
            $priceOnlyNumbers = str_replace($price, '.', '');
            if (preg_match('/[^0-9]/', $priceOnlyNumbers))
            {
                $priceErrs[] = "Cena musí byť číslo";
            }

            $numberOfDigits = 0;
            $index = 0;
            for ($index; $index < strlen($price); $index++)
            {
                $numberOfDigits++;
                if ($price[$index] == '.')
                    break;
            }
            if ($numberOfDigits - 1 > self::MAX_SIZE_BEFORE_POINT)
            {
                $priceErrs[] = "Celá časť nesmie mať viac ako ". self::MAX_SIZE_BEFORE_POINT . " číslic";
            }
            $numberOfDigits = strlen($price) - $numberOfDigits;

            if ($numberOfDigits > self::MAX_SIZE_AFTER_POINT)
            {
                $priceErrs[] = "Desatinná časť nesmie mať viac ako ". self::MAX_SIZE_AFTER_POINT . " číslice";
            }
        }
        return $priceErrs;
    }

    //region Getters and setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name): void
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    //endregion
}