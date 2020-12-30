<?php


namespace App\Models;

/**
 * Class BookReservationsPending is a helper class for getting reservations of users
 * @package App\Models
 */
class BookReservationsPending implements \JsonSerializable
{
    private $reservation_id;
    private $ISBN;
    private $name;
    private $book_id;
    private $email;
    private $request_date;
    private $reserve_day;
    private $return_day;

    /**
     * BookReservationsPending constructor.
     * @param $reservation_id;
     * @param $ISBN
     * @param $name
     * @param $book_id
     * @param $email
     * @param $request_date
     * @param $reserve_day
     * @param $return_day
     */
    public function __construct($ISBN = null, $name = null, $book_id = null, $email = null, $request_date = null, $reserve_day = null, $return_day = null, $reservation_id = null)
    {
        $this->reservation_id = $reservation_id;
        $this->ISBN = $ISBN;
        $this->name = $name;
        $this->book_id = $book_id;
        $this->email = $email;
        $this->request_date = $request_date;
        $this->reserve_day = $reserve_day;
        $this->return_day = $return_day;
    }

    public static function getDBColumns()
    {
        return ['reservation_id', 'ISBN', 'name', 'book_id', 'email' , 'request_date', 'reserve_day', 'return_day'];
    }

    /**
     * Sets the values from data
     * @param $data
     */
    public function setValues($data)
    {
        $this->reservation_id = $data['reservation_id'];
        $this->ISBN = $data['ISBN'];
        $this->name = $data['name'];
        $this->book_id = $data['book_id'];
        $this->email = $data['email'];
        $this->request_date = $data['request_date'];
        $this->reserve_day = $data['reserve_day'];
        $this->return_day = $data['return_day'];
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    // region Getters and setters

    /**
     * @return mixed|null
     */
    public function getReservationId()
    {
        return $this->reservation_id;
    }

    /**
     * @param mixed|null $reservation_id
     */
    public function setReservationId($reservation_id): void
    {
        $this->reservation_id = $reservation_id;
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
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @param mixed|null $book_id
     */
    public function setBookId( $book_id): void
    {
        $this->book_id = $book_id;
    }

    /**
     * @return mixed|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed|null $email
     */
    public function setEmail( $email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed|null
     */
    public function getRequestDate()
    {
        return $this->request_date;
    }

    /**
     * @param mixed|null $request_date
     */
    public function setRequestDate( $request_date): void
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
     * @param mixed|null $reserve_day
     */
    public function setReserveDay( $reserve_day): void
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
     * @param mixed|null $return_day
     */
    public function setReturnDay( $return_day): void
    {
        $this->return_day = $return_day;
    }

    //endregion
}