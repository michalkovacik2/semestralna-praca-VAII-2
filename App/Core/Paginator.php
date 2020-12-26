<?php

namespace App\Core;

class Paginator
{
    private int $itemsOnePage;
    private int $totalNumber;
    private string $link;
    private int $numberOfPages;

    public function __construct($numOfItemsOnOnePage, $totalNumberOfItems, $link)
    {
        $this->itemsOnePage = $numOfItemsOnOnePage;
        $this->totalNumber = $totalNumberOfItems;
        $this->link = $link;

        $numSites = intdiv($totalNumberOfItems, $numOfItemsOnOnePage);
        $this->numberOfPages = $totalNumberOfItems % $numOfItemsOnOnePage != 0 ? $numSites + 1 : $numSites;
    }


    /**
     * @param $page int
     * @param $data string
     * @param $param3
     * @return array
     */
    public function getData($page, $data, $param3, $func)
    {
        if ($page <= 0 || $page > $this->numberOfPages)
            return null;

        return $data::$func(($page - 1) * $this->itemsOnePage, $this->itemsOnePage, $param3);
    }

    public function getLayout($page) :string
    {
        $layout = '';

        if ($page <= 1)
        {
            $layout .= '<li class="page-item disabled">
                            <a class="page-link" href="#"> <i class="fas fa-arrow-left"></i> </a>
                        </li>';
        }
        else
        {
            $layout .= '<li class="page-item">
                            <a class="page-link" href=" ' . $this->link . ($page - 1 ). '"> <i class="fas fa-arrow-left"></i> </a>
                        </li>';
        }

        for ($i=1; $i <= $this->numberOfPages; $i++)
        {
            $layout .= '<li class="page-item">
                            <a class="page-link" href=" '. $this->link . $i .' "> ' . $i. ' </a>
                        </li>';
        }


        if ($page >= $this->numberOfPages)
        {
            $layout .= '<li class="page-item disabled">
                            <a class="page-link" href="#"> <i class="fas fa-arrow-right"></i> </a>
                        </li>';
        }
        else
        {
            $layout .= '<li class="page-item">
                            <a class="page-link" href="' . $this->link . ($page + 1 ). '"> <i class="fas fa-arrow-right"></i> </a>
                        </li>';
        }

        return $layout;
    }

}