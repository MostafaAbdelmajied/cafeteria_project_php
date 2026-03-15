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
