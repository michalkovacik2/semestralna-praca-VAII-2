<?php

namespace App\Core\Responses;

use App\App;
use App\Config\Configuration;

/**
 * Class ViewResponse
 * Response returning view
 * @package App\Core\Responses
 */
class ViewResponse extends Response
{
    private App $app;
    private $viewName;
    private $layoutName = Configuration::ROOT_LAYOUT;
    private $data;

    public function __construct($app, $viewName, $data)
    {
        $this->app = $app;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function generate() {

        $data = $this->data;
        $auth = $this->app->getAuth();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        $contentHTML = ob_get_clean();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->layoutName;

    }

    /**
     * @param mixed $layoutName
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;
        return $this;
    }

}