<?php
$pageTitle = 'Cafeteria | Add User';
require __DIR__ . '/layout/header.php';
$activePage = 'users';
require __DIR__ . '/layout/admin-header.php';
?>

    <main class="mx-auto w-full max-w-3xl px-6 py-10">
      <div class="rounded-3xl bg-white/90 p-8 shadow-2xl shadow-orange-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">User</p>
            <h1 class="text-2xl font-semibold">Add User</h1>
          </div>
          <a href="admin-users.php" class="text-xs font-semibold text-brand-600">Back to users</a>
        </div>

        <form class="mt-6 grid gap-4 sm:grid-cols-2" data-validate-form>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Name</label>
            <input type="text" required class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="Enter name" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" required data-validate="email" class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="Enter email" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Password</label>
            <input id="user-password" type="password" required data-validate="min" data-min="6" class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="Enter password" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Confirm Password</label>
            <input type="password" required data-validate="match" data-match="#user-password" class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="Confirm password" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Room No.</label>
            <input type="text" required class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="e.g. 2010" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div data-field class="sm:col-span-1">
            <label class="text-sm font-medium text-slate-700">Ext.</label>
            <input type="text" required class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none" placeholder="e.g. 5605" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>
          <div class="sm:col-span-2" data-field>
            <label class="text-sm font-medium text-slate-700">Profile Picture</label>
            <input type="file" class="mt-2 w-full rounded-2xl border border-dashed border-orange-200 bg-orange-50/50 px-4 py-3 text-sm" />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>

          <div class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600 sm:col-span-2" data-form-alert>
            Please fix the highlighted fields.
          </div>

          <div class="flex flex-wrap gap-3 sm:col-span-2">
            <button type="submit" class="rounded-2xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white">Save</button>
            <button type="reset" class="rounded-2xl border border-orange-200 px-6 py-3 text-sm font-semibold text-slate-600">Reset</button>
          </div>
        </form>
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
