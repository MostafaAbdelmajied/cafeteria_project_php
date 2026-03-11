<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;

class AdminController
{
    function index()
    {
        view("admin.php");
    }

    function adminProducts()
    {
        $perPage = 5;
        $currentPage = (int)($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalProducts = Product::query()->count();
        $totalPages = (int)ceil($totalProducts / $perPage);
        $products = Product::query()->limit($perPage)->offset($offset)->get();
        view("admin-products.php", compact("products", "totalPages", "currentPage"));
    }

    function adminOrders()
    {
        view("admin-orders.php");
    }

    function adminUsers()
    {
        $perPage = 5;
        $currentPage = (int)($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalUsers = User::query()->count();
        $totalPages = (int)ceil($totalUsers / $perPage);
        $users = User::query()->limit($perPage)->offset($offset)->get();
        view("admin-users.php", compact('users', "currentPage", "totalPages"));
    }
}