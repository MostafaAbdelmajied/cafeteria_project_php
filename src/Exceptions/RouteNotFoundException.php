<?php

namespace Src\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $message = "Route not found";
}