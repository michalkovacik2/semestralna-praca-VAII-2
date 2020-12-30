<?php

namespace App\Core;

/**
 * Class Paginator generates the paging on a site
 * @package App\Core
 */
class Paginator
{
    private int $itemsOnePage;
    private int $totalNumber;
    private string $link;
    private int $numberOfPages;

    /**
     * Paginator constructor.
     * @param int $numOfItemsOnOnePage - how many items do we want on one page
     * @param int $totalNumberOfItems - total number of all items
     * @param string $link - link where the paginator is used
     */
    public function __construct(int $numOfItemsOnOnePage, int $totalNumberOfItems, string $link)
    {
        $this->itemsOnePage = $numOfItemsOnOnePage;
        $this->totalNumber = $totalNumberOfItems;
        $this->link = $link;

        $numSites = intdiv($totalNumberOfItems, $numOfItemsOnOnePage);
        $this->numberOfPages = $totalNumberOfItems % $numOfItemsOnOnePage != 0 ? $numSites + 1 : $numSites;
    }

    /**
     * Gets the data which should be displayed on this site
     * @param int $page - which page we want
     * @param $data - class of data
     * @param $param3 - additional argument to the query
     * @param $func - which function to call to get the data
     * @return array
     */
    public function getData(int $page, $data, $param3, $func)
    {
        if ($page <= 0 || $page > $this->numberOfPages)
            return null;

        return $data::$func(($page - 1) * $this->itemsOnePage, $this->itemsOnePage, $param3);
    }

    /**
     * Gets the layout of the paginator
     * @param int $page
     * @return string
     */
    public function getLayout(int $page) :string
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