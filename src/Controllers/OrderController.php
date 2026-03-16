<?php

namespace Src\Controllers;

use Src\Classes\Auth;
use Src\Classes\DB;
use Src\Models\OrderItemModel;
use Src\Models\OrderModel;
use Src\Models\Product;
use Src\Models\User;
use Throwable;

class OrderController
{

// ================== Ali ===============================================================
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
            redirect(url('/my-orders'));
        }
    }

    private function validateDate($date)
    {
        if ($date === '') return '';
        // Accept only YYYY-MM-DD format to prevent SQL injection via date fields
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return '';
        return $date;
    }
    // =========================================================================================

    private function redirectWithOrderError(string $message, string $path = '/order-confirm'): void
    {
        $_SESSION['order_error'] = $message;
        redirect(url($path));
    }

    private function getAllowedRooms(): array
    {
        //  "SELECT DISTINCT room_no FROM users WHERE room_no IS NOT NULL AND room_no <> '' ORDER BY room_no"
        $users = User::all(['room_no']);
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

    private function validatePendingPayload(array $payload, string $redirectPath = '/'): array
    {
        if (empty($payload['items']) || !is_array($payload['items']) || count($payload['items']) === 0) {
            $this->redirectWithOrderError('Your cart must not be empty.', $redirectPath);
        }

        $note = trim((string) ($payload['note'] ?? ''));
        if (strlen($note) > 1000) {
            $this->redirectWithOrderError('Notes must be 1000 characters or fewer.', $redirectPath);
        }

        $room = trim((string) ($payload['room'] ?? ''));
        if ($room === '') {
            $this->redirectWithOrderError('Room is required.', $redirectPath);
        }

        $allowedRooms = $this->getAllowedRooms();
        if (!in_array($room, $allowedRooms, true)) {
            $this->redirectWithOrderError('Selected room is invalid.', $redirectPath);
        }

        foreach ($payload['items'] as $item) {
            $productId = (int) ($item['id'] ?? 0);
            $quantity = (int) ($item['qty'] ?? 0);

            if ($productId <= 0) {
                $this->redirectWithOrderError('One or more selected products are invalid.', $redirectPath);
            }

            if ($quantity < 1) {
                $this->redirectWithOrderError('Quantity must be at least 1.', $redirectPath);
            }
        }

        return [
            'note' => $note,
            'room' => $room,
        ];
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

            $validated = $this->validatePendingPayload($payload, '/');

            $_SESSION['cafeteria_pending_order'] = $payload;
            $_SESSION['cafeteria_cart'] = $payload['items'];
            $_SESSION['cafeteria_note'] = $validated['note'];
            $_SESSION['cafeteria_room'] = $validated['room'];

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

        $validated = $this->validatePendingPayload($pending);
        $room = $validated['room'];
        $note = $validated['note'];

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
        }

        unset($_SESSION['cafeteria_pending_order']);
        unset($_SESSION['cafeteria_cart']);
        unset($_SESSION['cafeteria_note']);
        unset($_SESSION['cafeteria_room']);

        $_SESSION['order_success'] = 'Your order has been confirmed.';
        $_SESSION['clear_order_storage'] = true;
        redirect(url('/'));
    }

    public function cancel()
    {
        if (!Auth::check()) {
            redirect(url('/login'));
        }

        unset($_SESSION['cafeteria_pending_order']);
        unset($_SESSION['cafeteria_cart']);
        unset($_SESSION['cafeteria_note']);
        unset($_SESSION['cafeteria_room']);

        $_SESSION['order_success'] = 'Your pending order has been canceled.';
        $_SESSION['clear_order_storage'] = true;

        redirect(url('/'));
    }

    // ================== Ali ===============================================================

    public function myOrders()
    {
        $userId   = (int) $_SESSION['user_id'];
        $fromDate = $this->validateDate(isset($_GET['from']) ? $_GET['from'] : '');
        $toDate   = $this->validateDate(isset($_GET['to'])   ? $_GET['to']   : '');

        $sql    = "SELECT id, delivery_room, total_amount, status, order_date FROM orders WHERE user_id = ?";
        $params = [$userId];

        if ($fromDate) {
            $sql .= " AND DATE(order_date) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate) {
            $sql .= " AND DATE(order_date) <= ?";
            $params[] = $toDate;
        }

        $sql .= " ORDER BY order_date DESC";
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
            redirect(url('/my-orders'));
        }

        $stmt = DB::conn()->prepare(
            "SELECT id, delivery_room, notes, total_amount, status, order_date
             FROM orders WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$order) {
            setFlash('error', 'Order not found.');
            redirect(url('/my-orders'));
        }

        $stmt = DB::conn()->prepare(
            "SELECT oi.quantity, oi.unit_price, p.name AS product_name
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /*
        foreach ($items as &$item) {
            $item['emoji'] = $this->resolveEmoji($item);
        }
        unset($item);
        */

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
            redirect(url('/my-orders'));
        }

        // 2. Fetch only what we need — never SELECT *
        $stmt = DB::conn()->prepare(
            "SELECT id, status FROM orders WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$order) {
            setFlash('error', 'Order not found.');
            redirect(url('/my-orders'));
        }

        if ($order['status'] !== 'Processing') {
            setFlash('error', 'Only processing orders can be cancelled.');
            redirect(url('/my-orders'));
        }

        OrderModel::update($orderId, ['status' => 'Cancelled']);
        setFlash('success', 'Order cancelled successfully.');
        redirect(url('/my-orders'));
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
            redirect(url('/home'));
        }

        $total = 0;
        foreach ($cart as $i) {
            if (!isset($i['price'], $i['qty'])) continue;
            $total += (float) $i['price'] * (int) $i['qty'];
        }

        $order = OrderModel::create([
            'user_id' => (int) $_SESSION['user_id'],
            'notes'   => $notes,
            'delivery_room'    => $room,
            'total_amount'   => round($total, 2),
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
        OrderItemModel::createMany($rows);

        setFlash('success', 'Order placed successfully!');
        redirect(url('/my-orders'));
    }
}

