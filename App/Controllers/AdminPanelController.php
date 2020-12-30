<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\ComplexQuery;
use App\Core\KeyNotFoundException;
use App\Models\Reservation;

/**
 * Class AdminPanelController represents controller for admin page
 * @package App\Controllers
 */
class AdminPanelController extends AControllerBase
{
    private const ROWS_PER_PAGE = 10;

    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     */
    public function index()
    {
        return $this->html(null);
    }

    /**
     * Method for AJAX. Return JSON with user reservation data.
     * Data: 'ISBN', 'Kniha', 'ID knihy', 'Užívateľ', 'Rezervované', 'Požičané', 'Vrátené'
     * Get param - 'like' - is used for autocomplete
     * Get param - 'page' - which page the client wants
     * @return \App\Core\Responses\JsonResponse
     * @throws \Exception
     */
    public function userReservation()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges()) {
            $this->redirect("semestralka?c=NotFound");
        }

        $getData = $this->app->getRequest()->getGet();
        $like = null;
        if (isset($getData['like']))
        {
            $like = $getData['like'];
            $like = addcslashes($like, '%_'); //escape % and _
        }

        $page = 1;
        if (isset($getData['page']))
        {
            $page = $getData['page'];
        }
        $page--;

        $data = ComplexQuery::getUserReservations($like, $page * self::ROWS_PER_PAGE, self::ROWS_PER_PAGE );
        array_unshift($data, [ 'ISBN', 'Kniha', 'ID knihy', 'Užívateľ', 'Rezervované', 'Požičané', 'Vrátené']);
        array_unshift($data, ['ALL' => ComplexQuery::getUserReservationsCount($like)]);
        return $this->json($data);
    }

    /**
     * Method that is called to modify reservation. It expects JSON data: command : '' , reservation_id : ''
     * Possible commands: 'delete', 'lend', 'return'
     * @return \App\Core\Responses\JsonResponse
     * @throws \Exception
     */
    public function modify()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges()) {
            $this->redirect("semestralka?c=NotFound");
        }

        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody);

        if (is_null($data))
        {
            return $this->json(['Error' => 'Post body je prázdne']);
        }

        if (!isset($data->command))
        {
            return $this->json(['Error' => 'Musíte zadať akciu']);
        }

        if (!isset($data->reservation_id))
        {
            return $this->json(['Error' => 'Musíte zadať id rezervácie']);
        }

        try
        {
            /** @var $reservation Reservation */
            $reservation = Reservation::getOne($data->reservation_id);
        }
        catch(KeyNotFoundException $e)
        {
            return $this->json(['Error' => 'Rezervácia so zadaným id neexistuje']);
        }

        if ($data->command == "delete")
        {
            if (!is_null($reservation->getReserveDay()) && is_null($reservation->getReturnDay()))
            {
                return $this->json(['Error' => 'Rezerváciu nemožno zrušiť kniha je požičaná']);
            }

            $reservation->delete();
            return $this->json(['Error' => '']);
        }
        else if ($data->command == "lend")
        {
            if (!is_null($reservation->getReturnDay()))
            {
                return $this->json(['Error' => 'Kniha už bola vrátená']);
            }

            $reservation->setReserveDay(date('Y-m-d'));
            $reservation->save();
            return $this->json(['Error' => '']);
        }
        else if ($data->command == "return")
        {
            if (is_null($reservation->getReserveDay()))
            {
                return $this->json(['Error' => 'Kniha ešte nebola požičaná']);
            }

            $reservation->setReturnDay(date('Y-m-d'));
            $reservation->save();
            return $this->json(['Error' => '']);
        }

        return $this->json(['Error' => 'Zadaný command neexistuje']);
    }
}