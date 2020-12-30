<?php

namespace App\Controllers;
use App\Core\AControllerBase;
use App\Core\KeyNotFoundException;
use App\Models\Price_list;

/**
 * Class PriceController represents controller for price list page
 * @package App\Controllers
 */
class PriceController extends AControllerBase
{
    /**
     * Method implemented from AControllerBase for index action
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function index()
    {
        $data = Price_list::getAllOrderBy(3, false);
        return $this->html([ 'data' => $data ]);
    }

    /**
     * Action to add new charge to price list.
     * @return \App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function add()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=Price");
        }

        $postData = $this->app->getRequest()->getPost();
        if (empty($postData))
        {
            return $this->html(null);
        }

        $name = $postData['name'];
        $price = $postData['price'];

        $price = str_replace(',', '.', $price);
        $res = $this->validate($name, $price);

        if(is_null($res))
        {
            $name = htmlspecialchars($name);
            $price = floatval($price);
            $p = new Price_list($name, $price);
            $p->save();
            $this->redirect("semestralka?c=Price");
        }
        else
        {
            return $this->html(['data' => ['name' => $name, 'price' => $price], 'errors' => $res]);
        }

        return $this->html(null);
    }

    /**
     * Action to edit existing charge in price list
     * @return \App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function edit()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=Price");
        }

        $postData = $this->app->getRequest()->getPost();
        $getData = $this->app->getRequest()->getGet();
        if (!isset($getData['id']))
        {
            return $this->html("semestralka?c=Price");
        }

        $priceData = null;
        $errs = null;
        try
        {
            /** @var $priceData Price_list */
            $priceData = Price_list::getOne($getData['id']);
        }
        catch (KeyNotFoundException $ex)
        {
            $this->redirect("semestralka?c=Price");
        }

        $name = $priceData->getName();
        $price = $priceData->getPrice();

        if (isset($postData['name']) || isset($postData['price']))
        {
            $name = htmlspecialchars($postData['name']);
            $price = $postData['price'];
            $nameErrs = Price_list::checkName($postData['name']);
            $priceErrs = Price_list::checkPrice($postData['price']);
            if (count($nameErrs) > 0 || count($priceErrs) > 0)
            {
                $errs = ['name' => $nameErrs, 'price' => $priceErrs];
            }
            else
            {
                $price = floatval($price);
                $priceData->setName($name);
                $priceData->setPrice($price);
                $priceData->save();
                $this->redirect("semestralka?c=Price");
            }
        }

        return $this->html(['data' => ['id' => $getData['id'] , 'name' => $name, 'price' => $price], 'errors' => $errs]);
    }

    /**
     * Action to delete a charge from price list
     * @throws \Exception
     */
    public function delete()
    {
        if (!$this->app->getAuth()->isLogged() || !$this->app->getAuth()->hasPrivileges())
        {
            $this->redirect("semestralka?c=Price");
        }

        $getData = $this->app->getRequest()->getGet();
        if (isset($getData['id']))
        {
            $priceData = null;
            try
            {
                $priceData = Price_list::getOne($getData['id']);
            }
            catch (KeyNotFoundException $ex)
            {
                $this->redirect("semestralka?c=Price");
            }

            $priceData->delete();
            $this->redirect("semestralka?c=Price");
        }
    }

    /**
     * Function used to validate entered data for price list.
     * If data is valid returns null otherwise it returns array with errors.
     * @param $name
     * @param $price
     * @return array|null
     */
    private function validate($name, $price)
    {
        $nameErrs = Price_list::checkName($name);
        $priceErrs = Price_list::checkPrice($price);

        if (count($nameErrs) > 0 || count($priceErrs) > 0)
        {
            return ['name' => $nameErrs, 'price' => $priceErrs];
        }

        return null;
    }
}