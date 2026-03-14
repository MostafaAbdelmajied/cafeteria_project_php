<?php

namespace Src\Exceptions;

class PermissionDeniedException extends \Exception
{
    protected $message = 'You do not have permission to access this resource.';
    protected $code = 403;
}