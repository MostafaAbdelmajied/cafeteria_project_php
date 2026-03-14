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
              <tr class="cursor-pointer" data-toggle="#admin-order-1">
                <td class="px-6 py-4">2015/02/02 10:30 AM</td>
                <td class="px-6 py-4">Abdulrahman Hamdy</td>
                <td class="px-6 py-4">2006</td>
                <td class="px-6 py-4">6506</td>
                <td class="px-6 py-4">
                  <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">deliver</button>
                </td>
              </tr>
              <tr id="admin-order-1" class="hidden">
                <td class="px-6 py-4" colspan="5">
                  <div class="flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                      <p class="text-sm font-semibold">Tea</p>
                      <p class="text-xs text-slate-500">5 LE · ×2</p>
                    </div>
                  </div>
                  <div class="mt-4 flex items-center justify-between border-t border-orange-100 pt-3 text-sm font-semibold">
                    <span>Total</span>
                    <span class="text-brand-700">EGP 34</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

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
              <tr class="cursor-pointer" data-toggle="#admin-order-2">
                <td class="px-6 py-4">2015/02/01 11:30 AM</td>
                <td class="px-6 py-4">Islam Askar</td>
                <td class="px-6 py-4">2010</td>
                <td class="px-6 py-4">5605</td>
                <td class="px-6 py-4">
                  <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">deliver</button>
                </td>
              </tr>
              <tr id="admin-order-2" class="hidden">
                <td class="px-6 py-4" colspan="5">
                  <div class="flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                      <p class="text-sm font-semibold">Coffee</p>
                      <p class="text-xs text-slate-500">6 LE · ×2</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-orange-50/70 px-4 py-3">
                      <p class="text-sm font-semibold">Tea</p>
                      <p class="text-xs text-slate-500">5 LE · ×1</p>
                    </div>
                  </div>
                  <div class="mt-4 flex items-center justify-between border-t border-orange-100 pt-3 text-sm font-semibold">
                    <span>Total</span>
                    <span class="text-brand-700">EGP 17</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
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
