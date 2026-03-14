<?php

namespace Src\Controllers;

use Src\Classes\DB;
use Src\Models\Order;
use Src\Models\OrderItem;

class OrderController
{
    private static $emojiMap = [
        'milk tea' => '🧋',
        'tea'      => '🍵',
        'coffee'   => '☕',
        'espresso' => '☕',
        'nescafe'  => '☕',
        'choco'    => '🍫',
        'cola'     => '🥤',
        'juice'    => '🧃',
        'water'    => '💧',
        'lemon'    => '🍋',
        'orange'   => '🍊',
    ];

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function resolveEmoji($item)
    {
        $emoji = isset($item['emoji']) ? trim($item['emoji']) : '';
        if ($emoji !== '' && $emoji !== '?' && $emoji !== '??') {
            return $emoji;
        }
        $name = strtolower(
            isset($item['product_name']) ? $item['product_name'] : (isset($item['name'])        ? $item['name'] : '')
        );
        foreach (self::$emojiMap as $keyword => $icon) {
            if (strpos($name, $keyword) !== false) return $icon;
        }
        return '☕';
    }

    private function verifyCsrf()
    {
        $token        = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        $sessionToken = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
            setFlash('error', 'Invalid request. Please try again.');
            redirect('/my-orders');
        }
    }

    private function validateDate($date)
    {
        if ($date === '') return '';
        // Accept only YYYY-MM-DD format to prevent SQL injection via date fields
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return '';
        return $date;
    }

    // ── Controllers ──────────────────────────────────────────────────────────

    public function myOrders()
    {
        $userId   = (int) $_SESSION['user_id'];
        $fromDate = $this->validateDate(isset($_GET['from']) ? $_GET['from'] : '');
        $toDate   = $this->validateDate(isset($_GET['to'])   ? $_GET['to']   : '');

        $sql    = "SELECT id, room, total, status, created_at FROM orders WHERE user_id = ?";
        $params = [$userId];

        if ($fromDate) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $toDate;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = DB::conn()->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        render('my-orders', compact('orders', 'fromDate', 'toDate'));
    }

    public function orderDetails()
    {
        $orderId = (int) (isset($_GET['id']) ? $_GET['id'] : 0);
        $userId  = (int) $_SESSION['user_id'];

        if ($orderId <= 0) {
            setFlash('error', 'Invalid order.');
            redirect('/my-orders');
        }

        $stmt = DB::conn()->prepare(
            "SELECT id, room, notes, total, status, created_at
             FROM orders WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$order) {
            setFlash('error', 'Order not found.');
            redirect('/my-orders');
        }

        $stmt = DB::conn()->prepare(
            "SELECT oi.quantity, oi.price, p.name AS product_name, p.emoji
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            $item['emoji'] = $this->resolveEmoji($item);
        }
        unset($item);

        render('order-details', compact('order', 'items'));
    }

    public function cancelOrder()
    {
        // 1. Verify CSRF token first
        $this->verifyCsrf();

        $orderId = (int) (isset($_POST['order_id']) ? $_POST['order_id'] : 0);
        $userId  = (int) $_SESSION['user_id'];

        if ($orderId <= 0) {
            setFlash('error', 'Invalid order.');
            redirect('/my-orders');
        }

        // 2. Fetch only what we need — never SELECT *
        $stmt = DB::conn()->prepare(
            "SELECT id, status FROM orders WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$order) {
            setFlash('error', 'Order not found.');
            redirect('/my-orders');
        }

        if ($order['status'] !== 'Processing') {
            setFlash('error', 'Only processing orders can be cancelled.');
            redirect('/my-orders');
        }

        Order::update($orderId, ['status' => 'Cancelled']);
        setFlash('success', 'Order cancelled successfully.');
        redirect('/my-orders');
    }

    public function placeOrder()
    {
        // Not owned by Person 3 — belongs to Person 2 (User Ordering System)
        // Kept here for routing compatibility only, do not modify
        $cart  = json_decode(isset($_POST['cart_data']) ? $_POST['cart_data'] : '[]', true);
        $notes = trim(isset($_POST['notes']) ? $_POST['notes'] : '');
        $room  = trim(isset($_POST['room'])  ? $_POST['room']  : '');

        if (!is_array($cart) || empty($cart) || !$room) {
            setFlash('error', 'Please add items and select a room.');
            redirect('/home');
        }

        $total = 0;
        foreach ($cart as $i) {
            if (!isset($i['price'], $i['qty'])) continue;
            $total += (float) $i['price'] * (int) $i['qty'];
        }

        $order = Order::create([
            'user_id' => (int) $_SESSION['user_id'],
            'notes'   => $notes,
            'room'    => $room,
            'total'   => round($total, 2),
            'status'  => 'Processing',
        ]);

        $rows = [];
        foreach ($cart as $i) {
            if (!isset($i['id'], $i['price'], $i['qty'])) continue;
            $rows[] = [
                'order_id'   => $order['id'],
                'product_id' => (int)   $i['id'],
                'quantity'   => (int)   $i['qty'],
                'price'      => (float) $i['price'],
            ];
        }
        OrderItem::createMany($rows);

        setFlash('success', 'Order placed successfully!');
        redirect('/my-orders');
    }
}
