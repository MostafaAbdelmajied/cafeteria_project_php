<?php

use Src\Exceptions\ViewNotFoundException;

function view($path, $data = []){
    extract($data);
    if(file_exists(__DIR__ . "/views/$path")){
        require_once __DIR__ . "/views/$path";
    }else{
        throw new ViewNotFoundException();
    }
}

function url($path){
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('\\', '/', dirname($scriptName));

    if ($basePath === '/' || $basePath === '.') {
        $basePath = '';
    }

    return rtrim($basePath, '/') . '/' . ltrim($path, '/');
}

function redirect($url)
{
    header("Location: $url");
    exit;
}


if (!function_exists('setFlash')) {
    function setFlash($type, $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('getFlash')) {
    function getFlash()
    {
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
        unset($_SESSION['flash']);
        return $flash;
    }
}

if (!function_exists('render')) {
    function render($view, $data = [])
    {
        extract($data);
        $path = __DIR__ . '/views/' . $view . '.php';
        if (!file_exists($path)) {
            http_response_code(404);
            require __DIR__ . '/views/errors/404.php';
            exit;
        }
        require $path;
    }
}