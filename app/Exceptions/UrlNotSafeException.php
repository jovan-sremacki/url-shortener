<?php

namespace App\Exceptions;

use Exception;

class UrlNotSafeException extends Exception
{
    public function __construct($message = "URL is not safe according to Google Safe Browsing", $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
