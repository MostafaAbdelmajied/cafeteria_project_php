<?php
if (!defined("BASE_PATH")) {
  header("Location: /");
  exit;
}

$pageTitle  = 'Cafeteria | Order Details';
$activePage = 'my-orders';
$order      = $order ?? [];
$items      = $items  ?? [];

// nothing to show, go back
if (empty($order)) {
  redirect('/my-orders');
}

$orderId   = (int) $order['id'];
$orderStatus = $order['status'];

// generate csrf token if not already set
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// badge colors per status
$statusColors = [
  'Processing'       => 'bg-amber-100 text-amber-700',
  'Out for delivery' => 'bg-blue-100 text-blue-700',
  'Done'             => 'bg-emerald-100 text-emerald-700',
  'Cancelled'        => 'bg-red-100 text-red-600',
];
$color = $statusColors[$orderStatus] ?? 'bg-gray-100 text-gray-600';

require __DIR__ . '/layout/header.php';
require __DIR__ . '/layout/user-header.php';
?>

<main class="mx-auto w-full max-w-3xl px-6 py-10">

  <!-- page title + back link -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Order #<?= $orderId ?></p>
      <h1 class="text-2xl font-semibold">Order Details</h1>
    </div>
    <a href="<?= BASE_PATH ?>/my-orders"
      class="text-sm font-semibold text-brand-600 hover:underline">← Back to orders</a>
  </div>

  <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">

    <!-- order info: date, room, notes, status badge -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-orange-100 px-6 py-5">
      <div class="space-y-1 text-sm text-slate-600">
        <p><span class="font-semibold">Date:</span> <?= htmlspecialchars($order['created_at']) ?></p>
        <p><span class="font-semibold">Room:</span> <?= htmlspecialchars($order['room']) ?></p>
        <?php if (!empty($order['notes'])): ?>
          <p><span class="font-semibold">Notes:</span> <?= htmlspecialchars($order['notes']) ?></p>
        <?php endif; ?>
      </div>
      <span class="rounded-full px-4 py-1 text-xs font-semibold <?= $color ?>">
        <?= htmlspecialchars($orderStatus) ?>
      </span>
    </div>

    <!-- items breakdown -->
    <table class="w-full text-left text-sm">
      <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
        <tr>
          <th class="px-6 py-3">Item</th>
          <th class="px-6 py-3 text-center">Qty</th>
          <th class="px-6 py-3 text-right">Unit Price</th>
          <th class="px-6 py-3 text-right">Subtotal</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-orange-100">
        <?php foreach ($items as $item):
          $qty      = (int)   $item['quantity'];
          $price    = (float) $item['price'];
          $subtotal = $price * $qty;
        ?>
          <tr>
            <td class="px-6 py-3 font-medium">
              <?= $item['emoji'] ?? '☕' ?> <?= htmlspecialchars($item['product_name']) ?>
            </td>
            <td class="px-6 py-3 text-center"><?= $qty ?></td>
            <td class="px-6 py-3 text-right"><?= number_format($price, 2) ?> EGP</td>
            <td class="px-6 py-3 text-right font-semibold"><?= number_format($subtotal, 2) ?> EGP</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <!-- order total spans first 3 cols to align with subtotal above -->
      <tfoot class="border-t-2 border-orange-200 bg-orange-50/60">
        <tr>
          <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-700">Total</td>
          <td class="px-6 py-4 text-right text-base font-bold text-brand-700">
            <?= number_format((float) $order['total'], 2) ?> EGP
          </td>
        </tr>
      </tfoot>
    </table>

    <!-- cancel only available while still processing -->
    <?php if ($orderStatus === 'Processing'): ?>
      <div class="border-t border-orange-100 px-6 py-4">
        <form method="POST" action="<?= BASE_PATH ?>/order/cancel"
          onsubmit="return confirm('Are you sure you want to cancel this order?')">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <input type="hidden" name="order_id" value="<?= $orderId ?>">
          <button type="submit"
            class="rounded-full bg-red-100 px-5 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-200">
            Cancel Order
          </button>
        </form>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php require __DIR__ . '/layout/footer.php'; ?>