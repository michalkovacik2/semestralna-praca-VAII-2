<?php

namespace App\Models;
use App\Core\Model;
use DateTime;

/**
 * Class User represents database entity user
 * @package App\Models
 */
class User extends Model
{
    private const MAX_SIZE_NAME = 30;
    private const MAX_SIZE_SURNAME = 50;
    private const MAX_SIZE_EMAIL = 320;
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_PHONE_LENGTH = 12;

    protected $email;
    protected $name;
    protected $surname;
    protected $phone;

    protected $password;
    protected $member_from;
    protected $payment_from;
    protected $permissions;

    /**
     * User constructor.
     * @param $email
     * @param $name
     * @param $surname
     * @param $phone
     * @param $password
     * @param $member_from
     * @param $payment_from
     * @param $permissions
     */
    public function __construct($email = null, $name = null, $surname = null, $phone = null,
                                $password = null, $member_from = null, $payment_from = null,
                                $permissions = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->password = $password;
        $this->member_from = $member_from;
        $this->payment_from = $payment_from;
        $this->permissions = $permissions;
    }

    /**
     * @return string[]
     */
    static public function setDbColumns()
    {
        return ['email', 'name', 'surname', 'phone', 'password',
                'member_from', 'payment_from', 'permissions'];
    }

    /**
     * @return string
     */
    static public function setPrimaryKeyColumnName()
    {
        return 'email';
    }

    /**
     * @return string
     */
    static public function setTableName()
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getPhoneFormated()
    {
        $res = "+";
        for ($i = 0; $i < strlen($this->phone); $i++)
        {
            if ($i % 3 == 0 && $i != 0)
                $res .= " ";

            $res .= $this->phone[$i];
        }
        return $res;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getMemberFromFormated()
    {
        $date = new DateTime($this->member_from);
        return $date->format('d.m.Y');
    }

    /**
     * Checks name of the user
     * @param $name
     * @return array
     */
    static public function checkName($name)
    {
        $nameErrs = [];
        if (empty($name))
        {
            $nameErrs[] = "Prosím zadajte meno";
        }
        else
        {
            if (strlen($name) > self::MAX_SIZE_NAME)
                $nameErrs[] = "Meno nesmie byť dlhšie ako " . self::MAX_SIZE_NAME;
        }
        return $nameErrs;
    }

    /**
     * Checks password of the user
     * @param $surname
     * @return array
     */
    static public function checkSurname($surname)
    {
        $surnameErrs = [];
        if (empty($surname))
        {
            $surnameErrs[] = "Prosím zadajte priezvisko";
        }
        else
        {
            if (strlen($surname) > self::MAX_SIZE_SURNAME)
                $surnameErrs[] = "Priezvisko nesmie byť dlhšie ako " . self::MAX_SIZE_SURNAME;
        }
        return $surnameErrs;
    }

    /**
     * Checks email of the user
     * @param $email
     * @return array
     * @throws \Exception
     */
    static public function checkEmail($email)
    {
        $emailErrs = [];
        if (empty($email))
        {
            $emailErrs[] = "Prosím zadajte email";
        }
        else
        {
            if (strlen($email) > self::MAX_SIZE_EMAIL)
                $emailErrs[] = "Email nemôže byť dlhší ako " . self::MAX_SIZE_EMAIL;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $emailErrs[] = "Zadaný email nie je správny";

            if (User::containsKey($email))
                $emailErrs[] = "Iný používateľ už má rovnaký email, zvoľte iný";
        }
        return $emailErrs;
    }

    /**
     * Checks phone number of user
     * @param $phone
     * @return array
     */
    static public function checkPhone($phone)
    {
        $phoneErrs = [];
        if (empty($phone))
        {
            $phoneErrs[] = "Prosím zadajte telefónne číslo";
        }
        else
        {
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('+', '', $phone);
            if (preg_match('/[^0-9]/', $phone))
                $phoneErrs[] = "Číslo musí pozostávať iba z číslic(prípadne +)";

            if (substr($phone, 0, 3) != "421")
                $phoneErrs[] = "Číslo musí mať formát +421 alebo 421";

            if (strlen($phone) > self::MAX_PHONE_LENGTH)
                $phoneErrs[] = "Číslo nesmie byť dlhšie ako ". self::MAX_PHONE_LENGTH . " číslic";
        }
        return $phoneErrs;
    }

    /**
     * Checks password of user
     * @param $password
     * @return array
     */
    static public function checkPassword($password)
    {
        $passwordErrs = [];
        if (empty($password))
        {
            $passwordErrs[] = "Prosím zadajte heslo";
        }
        else
        {
            if (strlen($password) < self::MIN_PASSWORD_LENGTH)
                $passwordErrs[] = "Dĺžka hesla musí byť aspoň 8 znakov";

            if (!(preg_match('/[^0-9]/', $password) && preg_match('/[0-9]/', $password)))
                $passwordErrs[] = "Heslo musí obsahovať ľubovoľné znaky a aspoň jednu číslicu";
        }
        return $passwordErrs;
    }

    // region Getters and Setters
    /**
     * @return mixed|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed|null
     */
    public function getMemberFrom()
    {
        return $this->member_from;
    }

    /**
     * @return mixed|null
     */
    public function getPaymentFrom()
    {
        return $this->payment_from;
    }

    /**
     * @return mixed|null
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);;
    }

    /**
     * @param $payment_from
     */
    public function setPaymentFrom($payment_from)
    {
        $this->payment_from = $payment_from;
    }

    /**
     * @param $name
     */
    public function setName( $name): void
    {
        $this->name = $name;
    }

    /**
     * @param $surname
     */
    public function setSurname( $surname): void
    {
        $this->surname = $surname;
    }

    // endregion

}