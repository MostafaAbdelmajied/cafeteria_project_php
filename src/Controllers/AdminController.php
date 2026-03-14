<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;
use Src\Models\OrderModel;
use Src\Models\OrderItemModel;

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

        // Fetch all orders with User info
        $rawOrders = OrderModel::query()
            ->join('users u', 'orders.user_id = u.id')
            ->orderBy('orders.order_date', 'DESC')
            ->get([
                'orders.id AS order_id',
                'orders.order_date',
                'orders.status',
                'orders.total_amount',
                'orders.delivery_room AS room',
                'u.name AS user_name',
                'u.ext'
            ]);

        $orders = [];
        $orderIds = [];

        foreach ($rawOrders as $row) {
            $orders[$row['order_id']] = [
                'id' => $row['order_id'],
                'order_date' => $row['order_date'],
                'status' => $row['status'],
                'total_amount' => $row['total_amount'],
                'room' => $row['room'],
                'user_name' => $row['user_name'],
                'ext' => $row['ext'],
                'items' => []
            ];
            $orderIds[] = $row['order_id'];
        }

        // Output Items for these Orders
        if (!empty($orderIds)) {
            $items = OrderItemModel::query()
                ->join('products p', 'order_items.product_id = p.id')
                ->whereIn('order_items.order_id', $orderIds)
                ->get([
                    'order_items.order_id',
                    'order_items.quantity',
                    'order_items.unit_price',
                    'p.name AS product_name',
                    'p.product_picture AS image'
                ]);

            foreach ($items as $item) {
                $orders[$item['order_id']]['items'][] = [
                    'product_name' => $item['product_name'],
                    'image' => $item['image'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price']
                ];
            }
        }

        return view("admin-orders.php", compact("activePage", "orders"));
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