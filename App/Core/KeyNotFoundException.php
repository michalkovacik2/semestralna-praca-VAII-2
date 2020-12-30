<?php
namespace App\Core;
use Exception;

/**
 * Class KeyNotFoundException is thrown when there is no data with this key in database
 * @package App\Core
 */
class KeyNotFoundException extends Exception {}