<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;
use Src\Models\OrderModel;
use Src\Models\OrderItemModel;
use \Src\Classes\DB;

class AdminController
{
    function index()
    {
        $activePage = 'home';
        return view("admin.php", compact("activePage"));
    }

    function adminChecks()
    {
        $activePage = 'checks';
        $pdo = DB::conn();

        // 1. Capture Filters
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $selectedUserId = $_GET['user_id'] ?? null;

        // Base Where Clause for Dates
        $dateCondition = "";
        $dateParams = [];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $dateCondition = " AND DATE(o.order_date) BETWEEN ? AND ?";
            $dateParams[] = $dateFrom;
            $dateParams[] = $dateTo;
        } elseif (!empty($dateFrom)) {
            $dateCondition = " AND DATE(o.order_date) >= ?";
            $dateParams[] = $dateFrom;
        } elseif (!empty($dateTo)) {
            $dateCondition = " AND DATE(o.order_date) <= ?";
            $dateParams[] = $dateTo;
        }

        // 2. Query 1: Left Table - User Totals
        $totalsQuery = "
            SELECT u.id as user_id, u.name as user_name, SUM(o.total_amount) as total_spent
            FROM users u
            JOIN orders o ON u.id = o.user_id
            WHERE 1=1 $dateCondition
            GROUP BY u.id, u.name
            ORDER BY total_spent DESC
        ";

        $stmt = $pdo->prepare($totalsQuery);
        $stmt->execute($dateParams);
        $userTotals = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Auto-select the first user if none was explicitly clicked
        if ($selectedUserId === null && !empty($userTotals)) {
            $selectedUserId = $userTotals[0]['user_id'];
        }

        // 3. Query 2: Right Table - Selected User's Orders
        $selectedUserOrders = [];
        $selectedUserName = "";

        if ($selectedUserId) {
            // Find the name for the header
            foreach ($userTotals as $tot) {
                if ($tot['user_id'] == $selectedUserId) {
                    $selectedUserName = $tot['user_name'];
                    break;
                }
            }

            $ordersQuery = "
                SELECT o.id, o.order_date, o.total_amount
                FROM orders o
                WHERE o.user_id = ? $dateCondition
                ORDER BY o.order_date DESC
            ";

            $stmt = $pdo->prepare($ordersQuery);
            $ordersParams = array_merge([$selectedUserId], $dateParams);
            $stmt->execute($ordersParams);
            $selectedUserOrders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return view("admin-checks.php", compact(
            "activePage",
            "userTotals",
            "selectedUserOrders",
            "selectedUserId",
            "selectedUserName",
            "dateFrom",
            "dateTo"
        ));
    }

    function adminProducts()
    {
        $perPage = 5;
        $currentPage = (int) ($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalProducts = Product::query()->count();
        $totalPages = (int) ceil($totalProducts / $perPage);
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
        $currentPage = (int) ($_GET["page"] ?? 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalUsers = User::query()->count();
        $totalPages = (int) ceil($totalUsers / $perPage);
        $users = User::query()->limit($perPage)->offset($offset)->get();
        $activePage = 'users';
        return view("admin-users.php", compact('users', "currentPage", "totalPages", "activePage"));
    }
}