<?php
$pageTitle = 'Cafeteria | Admin Orders';
require __DIR__ . '/layout/header.php';
$activePage = 'orders';
require __DIR__ . '/layout/admin-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Operations</p>
        <h1 class="text-2xl font-semibold">Orders</h1>
      </div>

      <div class="mt-6 space-y-6">

        <?php if (isset($_SESSION['admin_order_error'])): ?>
          <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
            <?= htmlspecialchars($_SESSION['admin_order_error'], ENT_QUOTES, 'UTF-8') ?>
          </div>
          <?php unset($_SESSION['admin_order_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin_order_success'])): ?>
          <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            <?= htmlspecialchars($_SESSION['admin_order_success'], ENT_QUOTES, 'UTF-8') ?>
          </div>
          <?php unset($_SESSION['admin_order_success']); ?>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
          <div class="rounded-3xl border border-orange-100 bg-white/90 p-8 text-center text-slate-500 shadow-lg shadow-orange-100">
            No orders found.
          </div>
        <?php else: ?>
          <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
            <table class="w-full text-left text-sm">
              <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                  <th class="px-6 py-4">Order Date</th>
                  <th class="px-6 py-4">Name</th>
                  <th class="px-6 py-4">Room</th>
                  <th class="px-6 py-4">Ext.</th>
                  <th class="px-6 py-4">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-orange-100">
                <?php foreach ($orders as $order): ?>
                  <tr class="cursor-pointer hover:bg-orange-50/50 transition" data-toggle="#admin-order-<?= $order['id'] ?>">
                    <td class="px-6 py-4"><?= htmlspecialchars($order['order_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($order['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($order['room'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($order['ext'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="px-6 py-4">
                      <?php if ($order['status'] === 'Processing'): ?>
                        <form method="post" action="<?= url('/admin/orders/deliver') ?>" class="inline-block" onclick="event.stopPropagation()">
                          <input type="hidden" name="order_id" value="<?= $order['id'] ?>" />
                          <button type="submit" class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition">
                            deliver
                          </button>
                        </form>
                      <?php else: ?>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">delivered</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr id="admin-order-<?= $order['id'] ?>" class="hidden bg-orange-50/30">
                    <td class="px-6 py-4" colspan="5">
                      <div class="flex flex-wrap gap-3">
                        <?php foreach ($order['items'] as $item): ?>
                          <div class="rounded-2xl border border-orange-100 bg-white px-4 py-3 flex items-center gap-3">
                            <p class="text-sm font-semibold"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-slate-500"><?= (float)$item['unit_price'] ?> LE · &times;<?= $item['quantity'] ?></p>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <div class="mt-4 flex items-center justify-between border-t border-orange-100 pt-3 text-sm font-semibold">
                        <span>Total</span>
                        <span class="text-brand-700">EGP <?= (float)$order['total_amount'] ?></span>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </main>

<?php
$inlineScript = <<<'JS'
      // Page-specific JS
      (() => {
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
