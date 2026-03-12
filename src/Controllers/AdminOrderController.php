<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;

class AdminOrderController
{
    private function redirectWithOrderError(string $message, string $path = '/admin-manual-order'): void
    {
        $_SESSION['admin_order_error'] = $message;
        redirect(url($path));
    }

    private function redirectWithProductError(string $message, string $path = '/admin-products'): void
    {
        $_SESSION['admin_product_error'] = $message;
        redirect(url($path));
    }

    private function getAllowedRooms($users): array
    {

        $rooms = [];

        foreach ($users as $user) {
            $roomNo = trim((string) ($user['room_no'] ?? ''));
            if ($roomNo !== '') {
                $rooms[$roomNo] = $roomNo;
            }
        }

        ksort($rooms);

        return array_values($rooms);
    }

    public function showManualOrder()
    {
        // get all users
        $users = User::all();

        // get all products
        $products = Product::all();

        // get all rooms
        $rooms = $this->getAllowedRooms($users);

        $activePage = 'manual-order';

        return view("admin-manual-order.php", compact("users", "products", "rooms", "activePage"));
    }
}