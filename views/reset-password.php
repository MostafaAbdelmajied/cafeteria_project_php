<?php
$pageTitle = 'Cafeteria | Reset Password';
require __DIR__ . '/layout/header.php';
?>
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
      <div class="w-full max-w-md rounded-3xl bg-white/90 p-8 shadow-2xl shadow-orange-100">
        <div class="flex items-center gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-2xl text-white">☕</div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-600">Cafeteria</p>
            <h1 class="text-2xl font-semibold">Choose a new password</h1>
          </div>
        </div>

        <p class="mt-2 text-sm text-slate-500">Set a new password for your account.</p>

        <form class="mt-6 space-y-4" data-validate-form method="post" action="<?= url('/reset-password') ?>">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>" />

          <div data-field>
            <label class="text-sm font-medium text-slate-700">New password</label>
            <input
              id="reset-password"
              type="password"
              name="password"
              required
              data-validate="min"
              data-min="6"
              class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100"
              placeholder="Enter a new password"
            />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>

          <div data-field>
            <label class="text-sm font-medium text-slate-700">Confirm password</label>
            <input
              type="password"
              name="password_confirmation"
              required
              data-validate="match"
              data-match="#reset-password"
              class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100"
              placeholder="Confirm your password"
            />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>

          <div class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" data-form-alert>
            Please fix the highlighted fields.
          </div>

          <button class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:bg-brand-700" type="submit">
            Reset password
          </button>

          <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
              <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
              <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
          <?php endif; ?>
        </form>

        <div class="mt-4 text-xs text-slate-500">
          <a class="font-medium text-brand-600 hover:text-brand-700" href="<?= url('/login') ?>">Back to login</a>
        </div>
      </div>
    </main>
<?php
$inlineScript = <<<'JS'
      (() => {
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
