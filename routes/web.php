<?php

use Src\Classes\Router;
use Src\Controllers\AuthController;
use Src\Controllers\HomeController;
use Src\Controllers\OrderController;
use Src\Controllers\ProductController;
use Src\Controllers\UserController;
use Src\Controllers\AdminOrderController;
use Src\Controllers\CheckController;
use Src\Middleware\AuthMiddleware;
use Src\Middleware\AdminMiddleware;

// ── Guest ─────────────────────────────────────────────────────────────────────
Router::get('/',       [AuthController::class, 'showLogin']);
Router::get('/login',  [AuthController::class, 'showLogin']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', [AuthController::class, 'logout']);

// ── Authenticated users ───────────────────────────────────────────────────────
Router::group(['middleware' => [AuthMiddleware::class]], function () {

    Router::get('/home',          [HomeController::class,  'index']);

    // ── Order responsibilities (Islam) ────────────────────────────────────────
    Router::get('/my-orders',     [OrderController::class, 'myOrders']);
    Router::get('/order-details', [OrderController::class, 'orderDetails']);
    Router::post('/order/place',  [OrderController::class, 'placeOrder']);
    Router::post('/order/cancel', [OrderController::class, 'cancelOrder']);
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Router::group(['middleware' => [AdminMiddleware::class]], function () {

    Router::get('/admin',                 [AdminOrderController::class, 'orders']);
    Router::get('/admin/orders',          [AdminOrderController::class, 'orders']);
    Router::post('/admin/orders/deliver', [AdminOrderController::class, 'deliver']);

    Router::get('/admin/manual-order',    [AdminOrderController::class, 'manualOrder']);
    Router::post('/admin/manual-order',   [AdminOrderController::class, 'placeManualOrder']);

    Router::get('/admin/products',        [ProductController::class, 'index']);
    Router::get('/admin/add-product',     [ProductController::class, 'create']);
    Router::post('/admin/add-product',    [ProductController::class, 'store']);
    Router::get('/admin/edit-product',    [ProductController::class, 'edit']);
    Router::post('/admin/edit-product',   [ProductController::class, 'update']);
    Router::post('/admin/toggle-product', [ProductController::class, 'toggle']);
    Router::post('/admin/delete-product', [ProductController::class, 'delete']);

    Router::get('/admin/users',           [UserController::class, 'index']);
    Router::get('/admin/add-user',        [UserController::class, 'create']);
    Router::post('/admin/add-user',       [UserController::class, 'store']);
    Router::get('/admin/edit-user',       [UserController::class, 'edit']);
    Router::post('/admin/edit-user',      [UserController::class, 'update']);
    Router::post('/admin/delete-user',    [UserController::class, 'delete']);

    Router::get('/admin/checks',          [CheckController::class, 'index']);
});
