<?php

namespace Src\Controllers;

use Src\Models\User;

class AdminController
{
    function index()
    {
        view("admin.php");
    }

    function adminProducts()
    {
        view("admin-products.php");
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
        $totalPages = (int) ceil($totalUsers / $perPage);
        $users = User::query()->limit($perPage)->offset($offset)->get();
        view("admin-users.php", compact('users', "currentPage", "totalPages"));
    }
}