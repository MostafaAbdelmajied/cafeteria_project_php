<?php

use Src\Classes\Router;
use Src\Exceptions\PermissionDeniedException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/routes/web.php';

date_default_timezone_set('Africa/Cairo');

set_exception_handler(function ($e){
    if ($e instanceof PermissionDeniedException){
        http_response_code(403);
        return view('errors/403.php', ['exception' => $e]);
    }
    http_response_code(500);
    return view('errors/500.php', ['exception' => $e]);
});

session_start();

Router::resolve();
