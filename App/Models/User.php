<?php

namespace App\Models;
use App\Core\Model;
use DateTime;

class User extends Model
{
    protected $email;
    protected $name;
    protected $surname;
    protected $phone;
    protected $password;
    protected $member_from;
    protected $payment_from;
    protected $permissions;

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

    static public function setDbColumns()
    {
        return ['email', 'name', 'surname', 'phone', 'password',
                'member_from', 'payment_from', 'permissions'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'email';
    }

    static public function setTableName()
    {
        return 'user';
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getPhone()
    {
        return $this->phone;
    }

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

    public function getPassword()
    {
        return $this->password;
    }

    public function getMemberFrom()
    {
        return $this->member_from;
    }

    public function getMemberFromFormated()
    {
        $date = new DateTime($this->member_from);
        return $date->format('d.m.Y');
    }

    public function getPaymentFrom()
    {
        return $this->payment_from;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }


    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPaymentFrom($payment_from)
    {
        $this->payment_from = $payment_from;
    }

}