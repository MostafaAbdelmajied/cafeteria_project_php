<?php
$pageTitle = 'Cafeteria | My Orders';
require __DIR__ . '/layout/header.php';
$activePage = 'my-orders';
require __DIR__ . '/layout/user-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">History</p>
          <h1 class="text-2xl font-semibold">My Orders</h1>
        </div>
        <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-orange-100 bg-white/80 p-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Date from</label>
            <input type="date" class="mt-1 block rounded-xl border border-orange-100 px-3 py-2 text-xs focus:border-brand-300 focus:outline-none" />
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Date to</label>
            <input type="date" class="mt-1 block rounded-xl border border-orange-100 px-3 py-2 text-xs focus:border-brand-300 focus:outline-none" />
          </div>
          <button class="mt-4 rounded-xl bg-brand-600 px-4 py-2 text-xs font-semibold text-white">Filter</button>
        </div>
      </div>

      <div class="mt-6 overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
        <table class="w-full text-left text-sm">
          <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
              <th class="px-6 py-4">Order Date</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Amount</th>
              <th class="px-6 py-4">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-orange-100">
            <tr class="cursor-pointer" data-toggle="#order-1">
              <td class="px-6 py-4">
                2015/02/02 10:30 AM
                <span class="ml-2 text-xs text-slate-400">+</span>
              </td>
              <td class="px-6 py-4">
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Processing</span>
              </td>
              <td class="px-6 py-4 font-semibold">55 EGP</td>
              <td class="px-6 py-4">
                <button class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">Cancel</button>
              </td>
            </tr>
            <tr id="order-1" class="hidden">
              <td class="px-6 py-4" colspan="4">
                <div class="flex flex-wrap gap-3">
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Tea</p>
                    <p class="text-xs text-slate-500">5 LE · ×5</p>
                  </div>
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Cola</p>
                    <p class="text-xs text-slate-500">10 LE · ×3</p>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="cursor-pointer" data-toggle="#order-2">
              <td class="px-6 py-4">
                2015/02/01 11:30 AM
                <span class="ml-2 text-xs text-slate-400">+</span>
              </td>
              <td class="px-6 py-4">
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Out for delivery</span>
              </td>
              <td class="px-6 py-4 font-semibold">20 EGP</td>
              <td class="px-6 py-4"></td>
            </tr>
            <tr id="order-2" class="hidden">
              <td class="px-6 py-4" colspan="4">
                <div class="flex flex-wrap gap-3">
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Coffee</p>
                    <p class="text-xs text-slate-500">6 LE · ×2</p>
                  </div>
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Nescafe</p>
                    <p class="text-xs text-slate-500">8 LE · ×1</p>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="cursor-pointer" data-toggle="#order-3">
              <td class="px-6 py-4">
                2015/01/01 11:35 AM
                <span class="ml-2 text-xs text-slate-400">+</span>
              </td>
              <td class="px-6 py-4">
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Done</span>
              </td>
              <td class="px-6 py-4 font-semibold">29 EGP</td>
              <td class="px-6 py-4"></td>
            </tr>
            <tr id="order-3" class="hidden">
              <td class="px-6 py-4" colspan="4">
                <div class="flex flex-wrap gap-3">
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Tea</p>
                    <p class="text-xs text-slate-500">5 LE · ×1</p>
                  </div>
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Coffee</p>
                    <p class="text-xs text-slate-500">6 LE · ×1</p>
                  </div>
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Nescafe</p>
                    <p class="text-xs text-slate-500">8 LE · ×1</p>
                  </div>
                  <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                    <p class="text-sm font-semibold">Cola</p>
                    <p class="text-xs text-slate-500">10 LE · ×1</p>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-6 flex items-center justify-end gap-2 text-xs">
        <button class="rounded-full border border-orange-200 px-3 py-1">‹</button>
        <button class="rounded-full bg-brand-600 px-3 py-1 text-white">1</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">›</button>
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
