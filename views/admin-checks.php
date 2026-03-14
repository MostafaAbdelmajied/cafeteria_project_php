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
              <tr class="bg-orange-50/60">
                <td class="px-6 py-4">Islam Askar</td>
                <td class="px-6 py-4 font-semibold">500 EGP</td>
              </tr>
              <tr>
                <td class="px-6 py-4">Abdulrahman Hamdy</td>
                <td class="px-6 py-4 font-semibold">110 EGP</td>
              </tr>
              <tr>
                <td class="px-6 py-4">Sayed Fathy</td>
                <td class="px-6 py-4 font-semibold">1000 EGP</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
          <div class="border-b border-orange-100 px-6 py-4">
            <h2 class="text-sm font-semibold">Islam Askar's Orders</h2>
          </div>
          <table class="w-full text-left text-sm">
            <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
              <tr>
                <th class="px-6 py-4">Order Date</th>
                <th class="px-6 py-4">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-orange-100">
              <tr>
                <td class="px-6 py-4">2015/02/02 10:30 AM</td>
                <td class="px-6 py-4 font-semibold">55 EGP</td>
              </tr>
              <tr>
                <td class="px-6 py-4">2015/02/01 11:30 AM</td>
                <td class="px-6 py-4 font-semibold">20 EGP</td>
              </tr>
              <tr>
                <td class="px-6 py-4">2015/01/01 11:35 AM</td>
                <td class="px-6 py-4 font-semibold">29 EGP</td>
              </tr>
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
