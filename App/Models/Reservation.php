<?php

namespace App\Models;
use App\Core\Model;

/**
 * Class Reservation represents database entity reservation
 * @package App\Models
 */
class Reservation extends Model
{
    protected $reservation_id;
    protected $request_date;
    protected $reserve_day;
    protected $return_day;
    protected $book_id;
    protected $email;

    /**
     * Reservation constructor.
     * @param $reservation_id
     * @param $request_date
     * @param $reserve_day
     * @param $return_day
     * @param $book_id
     * @param $email
     */
    public function __construct($request_date = null, $reserve_day = null, $return_day = null, $book_id = null, $email = null)
    {
        $this->reservation_id = null;
        $this->request_date = $request_date;
        $this->reserve_day = $reserve_day;
        $this->return_day = $return_day;
        $this->book_id = $book_id;
        $this->email = $email;
    }

    static public function setDbColumns()
    {
        return ['reservation_id', 'request_date', 'reserve_day', 'return_day', 'book_id', 'email'];
    }

    static public function setPrimaryKeyColumnName()
    {
        return 'reservation_id';
    }

    static public function setTableName()
    {
        return 'reservation';
    }

    // region Getters and setters

    /**
     * @return null
     */
    public function getReservationId()
    {
        return $this->reservation_id;
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

    //endregion

}