<?php

namespace Src\Exceptions;

use Throwable;

class RouteNotFoundException extends \Exception
{
    protected $message = "Route not found.";

    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        return parent::__construct($this->message. $message, $code, $previous);
    }
}