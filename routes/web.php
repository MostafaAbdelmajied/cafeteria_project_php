<?php

use Src\Classes\Router;
use Src\Controllers\UserController;

Router::get("/user", [UserController::class, "index"]);