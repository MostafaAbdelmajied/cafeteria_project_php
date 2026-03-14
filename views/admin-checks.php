<?php
$pageTitle = 'Cafeteria | Admin Checks';
require __DIR__ . '/layout/header.php';
$activePage = 'checks';
require __DIR__ . '/layout/admin-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Reports</p>
          <h1 class="text-2xl font-semibold">Checks</h1>
        </div>
        
        <form method="GET" action="<?= url('/admin/checks') ?>" class="flex flex-wrap items-center gap-3 rounded-2xl border border-orange-100 bg-white/80 p-3">
          <?php if ($selectedUserId): ?>
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($selectedUserId) ?>" />
          <?php endif; ?>
          
          <div>
            <label class="text-xs font-semibold text-slate-600">Date from</label>
            <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>" class="mt-1 block rounded-xl border border-orange-100 px-3 py-2 text-xs focus:border-brand-300 focus:outline-none" />
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Date to</label>
            <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>" class="mt-1 block rounded-xl border border-orange-100 px-3 py-2 text-xs focus:border-brand-300 focus:outline-none" />
          </div>
          <button type="submit" class="mt-4 rounded-xl bg-brand-600 px-4 py-2 text-xs font-semibold text-white">Filter</button>
        </form>
        
      </div>

      <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
          <table class="w-full text-left text-sm">
            <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
              <tr>
                <th class="px-6 py-4">User Name</th>
                <th class="px-6 py-4">Total Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-orange-100">
              <?php if (empty($userTotals)): ?>
                <tr>
                  <td class="px-6 py-4 text-center text-slate-500" colspan="2">No checks found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($userTotals as $tot): ?>
                  <!-- Active state styling if this user is selected -->
                  <tr class="<?= $tot['user_id'] == $selectedUserId ? 'bg-orange-50/60' : 'hover:bg-orange-50/30 transition' ?>">
                    <td class="px-6 py-4">
                      <!-- Clickable Name: Preserves date filters while changing user_id -->
                      <a href="<?= url('/admin/checks?user_id=' . $tot['user_id'] . '&date_from=' . urlencode($dateFrom) . '&date_to=' . urlencode($dateTo)) ?>" class="block w-full font-medium text-brand-700 hover:underline">
                        <?= htmlspecialchars($tot['user_name']) ?>
                      </a>
                    </td>
                    <td class="px-6 py-4 font-semibold"><?= number_format((float)$tot['total_spent'], 2) ?> EGP</td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100 h-fit">
          <div class="border-b border-orange-100 px-6 py-4 bg-orange-50/30">
            <h2 class="text-sm font-semibold">
              <?php if ($selectedUserName): ?>
                <?= htmlspecialchars($selectedUserName) ?>'s Orders
              <?php else: ?>
                User Orders
              <?php endif; ?>
            </h2>
          </div>
          <table class="w-full text-left text-sm">
            <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
              <tr>
                <th class="px-6 py-4">Order Date</th>
                <th class="px-6 py-4">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-orange-100">
              <?php if (empty($selectedUserOrders)): ?>
                <tr>
                  <td class="px-6 py-4 text-center text-slate-500" colspan="2">No specific orders found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($selectedUserOrders as $ord): ?>
                  <tr class="hover:bg-orange-50/20 transition">
                    <td class="px-6 py-4"><?= htmlspecialchars($ord['order_date']) ?></td>
                    <td class="px-6 py-4 font-semibold"><?= number_format((float)$ord['total_amount'], 2) ?> EGP</td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-end gap-2 text-xs">
        <button class="rounded-full bg-brand-600 px-3 py-1 text-white">1</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">2</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">3</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">…</button>
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
