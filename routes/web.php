<?php

use Src\Classes\Router;
use Src\Controllers\Admin\AdminProductsController;
use Src\Controllers\Admin\AdminUsersController;
use Src\Controllers\AdminController;
use Src\Controllers\AdminOrderController;
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

     // order routes from ali
    Router::get('/my-orders',     [OrderController::class, 'myOrders']);
    Router::get('/order-details', [OrderController::class, 'orderDetails']);
    Router::post('/order/place',  [OrderController::class, 'placeOrder']);
    Router::post('/order/cancel', [OrderController::class, 'cancelOrder']);

});

Router::group(['middleware' => TypeMiddleware::class . ':admin'], function () {
    //admin routes
    Router::get("/admin-manual-order", [AdminOrderController::class, "showManualOrder"]);
    Router::post("/admin/manual-order/submit", [AdminOrderController::class, "submitManualOrder"]);
    Router::post("/admin/orders/deliver", [AdminOrderController::class, "markAsDelivered"]);
    Router::get("/admin/checks", [AdminController::class, "adminChecks"]);
    Router::get("/admin/orders", [AdminController::class, "adminOrders"]);

    Router::get("/admin", [AdminProductsController::class, "index"]);
    // admin products routes
    Router::get("/admin/products", [AdminProductsController::class, "products"]);
    Router::get("/admin/products/create", [AdminProductsController::class, "createProduct"]);
    Router::post("/admin/products/store", [AdminProductsController::class, "storeProduct"]);
    Router::post("/admin/products/delete", [AdminProductsController::class, "destroyProduct"]);
    Router::get("/admin/products/edit", [AdminProductsController::class, "editProduct"]);
    Router::post("/admin/products/update", [AdminProductsController::class, "updateProduct"]);
    Router::post("/admin/products/toggle-available", [AdminProductsController::class, "toggleProductAvailability"]);

    //admin users routes
    Router::get("/admin/users", [AdminUsersController::class, "users"]);
    Router::get("/admin/users/create", [AdminUsersController::class, "createUser"]);
    Router::post("/admin/users/store", [AdminUsersController::class, "storeUser"]);
    Router::post("/admin/users/delete", [AdminUsersController::class, "destroyUser"]);
    Router::get("/admin/users/edit", [AdminUsersController::class, "editUser"]);
    Router::post("/admin/users/update", [AdminUsersController::class, "updateUser"]);
});

Router::get("/logout", [AuthController::class, "logout"]);
