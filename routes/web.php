<?php

use Src\Classes\Router;
use Src\Controllers\UserController;
use Src\Controllers\AdminController;

Router::get("/user", [UserController::class, "index"]);

//admin routes
Router::get("/admin", [AdminController::class,"index"]);
Router::get("/admin/products", [AdminController::class, "adminProducts"]);
Router::get("/admin/orders", [AdminController::class, "adminOrders"]);
Router::get("/admin/users", [AdminController::class, "adminUsers"]);