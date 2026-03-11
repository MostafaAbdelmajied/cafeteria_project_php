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
    return dirname($_SERVER['SCRIPT_NAME']) . $path;
}