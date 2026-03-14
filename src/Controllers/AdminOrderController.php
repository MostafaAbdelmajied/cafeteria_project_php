<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Models\Product;
use Src\Classes\DB;
use Src\Models\OrderModel;
use Src\Models\OrderItemModel;
use Throwable;

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

    public function submitManualOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('/admin-manual-order'));
            return;
        }

        $userId = trim((string) ($_POST['user_id'] ?? ''));
        if ($userId === '') {
            $this->redirectWithOrderError('Please select a user.');
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->redirectWithOrderError('Selected user does not exist.');
            return;
        }

        $payload = [];
        if (!empty($_POST['cart_data'])) {
            $payload = json_decode($_POST['cart_data'], true) ?: [];
        }

        if (empty($payload['items']) || !is_array($payload['items']) || count($payload['items']) === 0) {
            $this->redirectWithOrderError('The cart must not be empty.');
            return;
        }

        $note = trim((string) ($payload['note'] ?? ''));
        if (strlen($note) > 1000) {
            $this->redirectWithOrderError('Notes must be 1000 characters or fewer.');
            return;
        }

        $room = trim((string) ($payload['room'] ?? ''));
        if ($room === '') {
            $this->redirectWithOrderError('Room is required.');
            return;
        }

        $pdo = DB::conn();

        try {
            $items = $payload['items'];
            $productIds = [];
            $normalizedItems = [];

            foreach ($items as $item) {
                $productId = (int) ($item['id'] ?? 0);
                $quantity = (int) ($item['qty'] ?? 0);

                if ($productId <= 0) {
                    throw new \RuntimeException('One or more selected products are invalid.');
                }

                if ($quantity < 1) {
                    throw new \RuntimeException('Quantity must be at least 1.');
                }

                $productIds[] = $productId;
                $normalizedItems[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ];
            }

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->get(['id', 'price', 'is_available']);

            if (count($products) !== count($normalizedItems)) {
                throw new \RuntimeException('One or more products could not be found.');
            }

            $totalAmount = 0.0;
            foreach ($products as $product) {
                $productId = (int) $product['id'];
                if (!(bool) $product['is_available']) {
                    throw new \RuntimeException('One or more selected products are unavailable.');
                }

                $unitPrice = (float) $product['price'];
                $normalizedItems[$productId]['unit_price'] = $unitPrice;
                $normalizedItems[$productId]['subtotal'] = $unitPrice * $normalizedItems[$productId]['quantity'];
                $totalAmount += $normalizedItems[$productId]['subtotal'];
            }

            $pdo->beginTransaction();

            $order = OrderModel::create([
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'Processing',
                'total_amount' => number_format($totalAmount, 2, '.', ''),
                'notes' => $note,
                'delivery_room' => $room,
                'user_id' => (int) $user['id'],
            ]);

            if (!$order || empty($order['id'])) {
                throw new \RuntimeException('The order could not be saved.');
            }

            $orderId = (int) $order['id'];

            foreach ($normalizedItems as $item) {
                $savedItem = OrderItemModel::create([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => number_format($item['unit_price'], 2, '.', ''),
                ]);

                if ($savedItem === false) {
                    throw new \RuntimeException('One or more order items could not be saved.');
                }
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $this->redirectWithOrderError($e->getMessage());
            return;
        }

        $_SESSION['admin_order_success'] = 'Order successfully created for ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '.';
        redirect(url('/admin-manual-order'));
    }

    public function markAsDelivered()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('/admin/orders'));
            return;
        }

        $orderId = trim((string) ($_POST['order_id'] ?? ''));

        if ($orderId === '') {
            $_SESSION['admin_order_error'] = 'Invalid order ID.';
            redirect(url('/admin/orders'));
            return;
        }

        $order = OrderModel::find($orderId);
        if (!$order) {
            $_SESSION['admin_order_error'] = 'Order not found.';
            redirect(url('/admin/orders'));
            return;
        }

        OrderModel::update($orderId, ['status' => 'Done']);
        
        $_SESSION['admin_order_success'] = 'Order marked as delivered successfully!';
        redirect(url('/admin/orders'));
    }
}