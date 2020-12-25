<?php

namespace App\Core\Responses;

use App\Config\Configuration;

/**
 * Class RedirectResponse
 * Goal of this class is to redirect user to site given as parameter redirectUrl
 * @package App\Core\Responses
 */
class RedirectResponse extends Response
{
    private string $redirectUrl;

    /**
     * RedirectResponse constructor.
     * @param string $redirectUrl
     */
    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        $this->generate();
    }

    /**
     * Redirects user, but if debug is on then waits.
     */
    public function generate()
    {
        if (!Configuration::DEBUG_QUERY)
            header('Location: '. $this->redirectUrl);
        else
            echo 'SQL debug <a href="' . $this->redirectUrl . '" > '. $this->redirectUrl ;
    }
}