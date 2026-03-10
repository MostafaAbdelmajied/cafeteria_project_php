<?php

namespace Src\Exceptions;

class ViewNotFoundException extends \Exception
{
    protected $message = "View not found";
}