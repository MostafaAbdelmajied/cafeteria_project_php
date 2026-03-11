<?php

use Src\Classes\Router;
use Src\Controllers\AdminController;
use Src\Controllers\AuthController;
use Src\Controllers\Home\HomeController;
use Src\Controllers\OrderController;
use Src\Controllers\PasswordResetController;
use Src\Controllers\UserController;
use Src\Middleware\Guest;
use Src\Middleware\TypeMiddleware;

Router::group(['middleware' => Guest::class], function () {
    Router::get("/login", [AuthController::class, "showLogin"]);
    Router::post("/login", [AuthController::class, "login"]);
    Router::get("/forgot-password", [PasswordResetController::class, "showForgotPassword"]);
    Router::post("/forgot-password", [PasswordResetController::class, "sendResetLink"]);
    Router::get("/reset-password", [PasswordResetController::class, "showResetPassword"]);
    Router::post("/reset-password", [PasswordResetController::class, "resetPassword"]);
});

Router::group(['middleware' => TypeMiddleware::class . ':user'], function () {
    Router::get("/", [HomeController::class, "index"]);
    Router::get("/user", [UserController::class, "index"]);
    Router::get("/order-confirm", [OrderController::class, "confirm"]);
    Router::post("/order-confirm", [OrderController::class, "confirm"]);
    Router::post("/order-cancel", [OrderController::class, "cancel"]);
    Router::post("/order-submit", [OrderController::class, "submit"]);

});

Router::group(['middleware' => TypeMiddleware::class . ':admin'], function () {
    //admin routes
    Router::get("/admin", [AdminController::class, "index"]);
    Router::get("/admin/products", [AdminController::class, "adminProducts"]);
    Router::get("/admin/orders", [AdminController::class, "adminOrders"]);
    Router::get("/admin/users", [AdminController::class, "adminUsers"]);
});

Router::get("/logout", [AuthController::class, "logout"]);
