<?php

namespace Src\Controllers;

use Src\Classes\Auth;
use Src\Classes\DB;
use Src\Models\OrderItemModel;
use Src\Models\OrderModel;
use Src\Models\Product;
use Throwable;

class OrderController
{
    private function redirectWithOrderError(string $message, string $path = '/order-confirm'): void
    {
        $_SESSION['order_error'] = $message;
        redirect(url($path));
    }

    public function confirm()
    {
        // Handle POST from cart page, set session order data and redirect back to GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::check()) {
                redirect(url('/login'));
            }

            $payload = [];
            if (!empty($_POST['cart_data'])) {
                $payload = json_decode($_POST['cart_data'], true) ?: [];
            }

            if (empty($payload['items']) || !is_array($payload['items']) || count($payload['items']) === 0) {
                $this->redirectWithOrderError('Your cart must not be empty.', '/');
            }

            $room = trim((string) ($payload['room'] ?? ''));
            if ($room === '') {
                $this->redirectWithOrderError('Room is required.', '/');
            }

            $_SESSION['cafeteria_pending_order'] = $payload;
            $_SESSION['cafeteria_cart'] = $payload['items'];
            $_SESSION['cafeteria_note'] = $payload['note'] ?? '';
            $_SESSION['cafeteria_room'] = $room;

            redirect(url('/order-confirm'));
        }

        // GET: show review page
        $pendingOrder = $_SESSION['cafeteria_pending_order'] ?? null;
        if (!is_array($pendingOrder)) {
            $pendingOrder = null;
        }
        view('order-confirm.php', compact('pendingOrder'));
    }

    public function submit()
    {
        // Final submit action
        $pending = $_SESSION['cafeteria_pending_order'] ?? null;
        $user = Auth::user();

        if (!$user) {
            redirect(url('/login'));
        }

        if (!$pending || empty($pending['items']) || !is_array($pending['items'])) {
            $this->redirectWithOrderError('Your cart must not be empty.', '/');
        }

        $room = trim((string) ($pending['room'] ?? ''));
        if ($room === '') {
            $this->redirectWithOrderError('Room is required.');
        }

        $pdo = DB::conn();

        try {
            $items = $pending['items'];
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
                'notes' => trim((string) ($pending['note'] ?? '')),
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
        }

        unset($_SESSION['cafeteria_pending_order']);
        unset($_SESSION['cafeteria_cart']);
        unset($_SESSION['cafeteria_note']);
        unset($_SESSION['cafeteria_room']);

        $_SESSION['order_success'] = 'Your order has been confirmed.';
        $_SESSION['clear_order_storage'] = true;
        redirect(url('/'));
    }
}

