<?php

namespace Src\Controllers;

use Src\Classes\Auth;
use Src\Classes\DB;
use Throwable;

class OrderController
{
    public function confirm()
    {
        // Handle POST from cart page, set session order data and redirect back to GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [];
            if (!empty($_POST['cart_data'])) {
                $payload = json_decode($_POST['cart_data'], true) ?: [];
            }

            if (empty($payload['items']) || count($payload['items']) === 0) {
                // no items: redirect back to home
                redirect(url('/'));
            }

            $_SESSION['cafeteria_pending_order'] = $payload;
            $_SESSION['cafeteria_cart'] = $payload['items'];
            $_SESSION['cafeteria_note'] = $payload['note'] ?? '';
            $_SESSION['cafeteria_room'] = $payload['room'] ?? '';

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
            redirect(url('/'));
        }

        $pdo = DB::conn();

        try {
            $items = $pending['items'];
            $productIds = [];
            $normalizedItems = [];

            foreach ($items as $item) {
                $productId = (int) ($item['id'] ?? 0);
                $quantity = (int) ($item['qty'] ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    throw new \RuntimeException('The order contains invalid items.');
                }

                $productIds[] = $productId;
                $normalizedItems[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ];
            }

            $placeholders = implode(', ', array_fill(0, count($productIds), '?'));
            $productsStmt = $pdo->prepare(
                "SELECT id, price, is_available FROM products WHERE id IN ($placeholders)"
            );
            $productsStmt->execute($productIds);
            $products = $productsStmt->fetchAll(\PDO::FETCH_ASSOC);

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

            $orderStmt = $pdo->prepare(
                'INSERT INTO orders (order_date, status, total_amount, notes, delivery_room, user_id) VALUES (NOW(), ?, ?, ?, ?, ?)'
            );
            $orderStmt->execute([
                'Processing',
                number_format($totalAmount, 2, '.', ''),
                trim((string) ($pending['note'] ?? '')),
                trim((string) ($pending['room'] ?? ($user['room_no'] ?? ''))),
                (int) $user['id'],
            ]);

            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)'
            );

            foreach ($normalizedItems as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    number_format($item['unit_price'], 2, '.', ''),
                ]);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $_SESSION['order_error'] = $e->getMessage();
            redirect(url('/order-confirm'));
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

