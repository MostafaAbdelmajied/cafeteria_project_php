<?php
$pageTitle = 'Cafeteria | Admin Products';
require __DIR__ . '/layout/header.php';
$activePage = 'products';
require __DIR__ . '/layout/admin-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Catalog</p>
          <h1 class="text-2xl font-semibold">All Products</h1>
        </div>
        <a href="admin-add-product.php" class="rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white">Add Product</a>
      </div>

      <div class="mt-6 overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
        <table class="w-full text-left text-sm">
          <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
              <th class="px-6 py-4">Product</th>
              <th class="px-6 py-4">Price</th>
              <th class="px-6 py-4">Image</th>
              <th class="px-6 py-4">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-orange-100">
            <tr>
              <td class="px-6 py-4">Tea</td>
              <td class="px-6 py-4">5 EGP</td>
              <td class="px-6 py-4 text-lg">☕</td>
              <td class="px-6 py-4">
                <div class="flex flex-wrap items-center gap-2">
                  <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">available</button>
                  <button class="rounded-full border border-orange-200 px-3 py-1 text-xs">edit</button>
                  <button class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td class="px-6 py-4">Nescafe</td>
              <td class="px-6 py-4">7 EGP</td>
              <td class="px-6 py-4 text-lg">☕</td>
              <td class="px-6 py-4">
                <div class="flex flex-wrap items-center gap-2">
                  <button class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">unavailable</button>
                  <button class="rounded-full border border-orange-200 px-3 py-1 text-xs">edit</button>
                  <button class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td class="px-6 py-4">Coffee</td>
              <td class="px-6 py-4">6 EGP</td>
              <td class="px-6 py-4 text-lg">☕</td>
              <td class="px-6 py-4">
                <div class="flex flex-wrap items-center gap-2">
                  <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">available</button>
                  <button class="rounded-full border border-orange-200 px-3 py-1 text-xs">edit</button>
                  <button class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-6 flex items-center justify-end gap-2 text-xs">
        <button class="rounded-full border border-orange-200 px-3 py-1">«</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">‹</button>
        <button class="rounded-full bg-brand-600 px-3 py-1 text-white">1</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">2</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">3</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">›</button>
        <button class="rounded-full border border-orange-200 px-3 py-1">»</button>
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
