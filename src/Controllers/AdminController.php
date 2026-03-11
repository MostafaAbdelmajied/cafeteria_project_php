<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;

class AdminController
{
    function index()
    {
        $activePage = 'home';
        return view("admin.php", compact("activePage"));
    }

    function adminProducts()
    {
        $perPage = 5;
        $currentPage = (int)($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalProducts = Product::query()->count();
        $totalPages = (int)ceil($totalProducts / $perPage);
        $products = Product::query()->limit($perPage)->offset($offset)->get();
        $activePage = 'products';
        return view("admin-products.php", compact("products", "totalPages", "currentPage", "activePage"));
    }

    function adminOrders()
    {
        $activePage = 'orders';
        return view("admin-orders.php", compact("activePage"));
    }

    function adminUsers()
    {
        $perPage = 5;
        $currentPage = (int)($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalUsers = User::query()->count();
        $totalPages = (int)ceil($totalUsers / $perPage);
        $users = User::query()->limit($perPage)->offset($offset)->get();
        $activePage = 'users';
        return view("admin-users.php", compact('users', "currentPage", "totalPages", "activePage"));
    }
}