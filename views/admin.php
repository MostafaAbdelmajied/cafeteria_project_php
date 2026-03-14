<?php
$pageTitle = 'Cafeteria | Admin Dashboard';
require __DIR__ . '/layout/header.php';
$activePage = 'home';
require __DIR__ . '/layout/admin-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="grid gap-8 lg:grid-cols-[1.05fr_1.6fr]">
        <section class="rounded-3xl bg-white/90 p-6 shadow-lg shadow-orange-100">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Manual Order</h2>
            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-brand-700">Room 2010</span>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between rounded-2xl border border-orange-100 bg-orange-50/60 px-4 py-3">
              <div>
                <p class="font-medium">Tea</p>
                <p class="text-xs text-slate-500">Extra sugar</p>
              </div>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-full bg-white px-3 py-1 text-sm">
                  <button class="text-brand-600">−</button>
                  <span>5</span>
                  <button class="text-brand-600">+</button>
                </div>
                <span class="font-semibold text-slate-700">EGP 25</span>
              </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl border border-orange-100 bg-white px-4 py-3">
              <div>
                <p class="font-medium">Cola</p>
                <p class="text-xs text-slate-500">Chilled</p>
              </div>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1 text-sm">
                  <button class="text-brand-600">−</button>
                  <span>3</span>
                  <button class="text-brand-600">+</button>
                </div>
                <span class="font-semibold text-slate-700">EGP 30</span>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <label class="text-sm font-semibold text-slate-700">Notes</label>
            <textarea class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100" rows="3">1 Tea Extra Sugar</textarea>
          </div>

          <div class="mt-4">
            <label class="text-sm font-semibold text-slate-700">Room</label>
            <select class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100">
              <option>2010</option>
              <option>2011</option>
              <option>3010</option>
            </select>
          </div>

          <div class="mt-6 flex items-center justify-between border-t border-orange-100 pt-4 text-base font-semibold">
            <span>Total</span>
            <span class="text-brand-700">EGP 55</span>
          </div>

          <button class="mt-6 w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:bg-brand-700">Confirm</button>
        </section>

        <section>
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Assign order</p>
              <h2 class="text-2xl font-semibold">Add to a user</h2>
            </div>
            <div class="rounded-2xl border border-orange-100 bg-white/80 px-4 py-2 text-sm">
              <label class="text-xs font-semibold text-slate-600">User</label>
              <select class="mt-1 block rounded-xl border border-orange-100 px-3 py-2 text-sm focus:border-brand-300 focus:outline-none">
                <option>Islam Askar</option>
                <option>Abdulrahman Hamdy</option>
                <option>Sayed Fathy</option>
              </select>
            </div>
          </div>

          <div class="mt-6 flex items-center gap-2 rounded-full border border-orange-100 bg-white px-4 py-2 text-sm">
            <span>🔍</span>
            <input class="w-48 bg-transparent text-sm focus:outline-none" placeholder="Search products..." />
          </div>

          <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
              <div class="text-3xl">🍵</div>
              <p class="mt-3 font-semibold">Tea</p>
              <p class="text-sm text-slate-500">5 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
              <div class="text-3xl">☕</div>
              <p class="mt-3 font-semibold">Coffee</p>
              <p class="text-sm text-slate-500">6 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
              <div class="text-3xl">🥤</div>
              <p class="mt-3 font-semibold">Cola</p>
              <p class="text-sm text-slate-500">10 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
              <div class="text-3xl">🧃</div>
              <p class="mt-3 font-semibold">Juice</p>
              <p class="text-sm text-slate-500">12 LE</p>
            </div>
          </div>
        </section>
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
