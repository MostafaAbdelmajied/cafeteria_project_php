<?php
$pageTitle = 'Cafeteria | Forgot Password';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
require __DIR__ . '/layout/header.php';
?>
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
      <div class="w-full max-w-md rounded-3xl bg-white/90 p-8 shadow-2xl shadow-orange-100">
        <div class="flex items-center gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-2xl text-white">☕</div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-600">Cafeteria</p>
            <h1 class="text-2xl font-semibold">Reset your password</h1>
          </div>
        </div>

        <p class="mt-2 text-sm text-slate-500">Enter your account email and generate a reset link.</p>

        <form class="mt-6 space-y-4" data-validate-form method="post" action="<?= url('/forgot-password') ?>">
          <div data-field>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input
              type="email"
              name="email"
              required
              data-validate="email"
              value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100"
              placeholder="you@company.com"
            />
            <p class="mt-1 hidden text-xs text-red-600" data-error></p>
          </div>

          <div class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600" data-form-alert>
            Please fix the highlighted fields.
          </div>

          <button class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:bg-brand-700" type="submit">
            Generate reset link
          </button>

          <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600">
              <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
              <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['status'])): ?>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs text-emerald-700">
              <p><?= htmlspecialchars($_SESSION['status'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php unset($_SESSION['status']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['reset_link'])): ?>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-slate-700">
              <p class="font-semibold text-slate-900">Generated reset link</p>
              <a class="mt-2 block break-all font-medium text-brand-700 hover:text-brand-800" href="<?= htmlspecialchars($_SESSION['reset_link'], ENT_QUOTES, 'UTF-8') ?>">
                your link is ready, click here to reset your password
              </a>
            </div>
            <?php unset($_SESSION['reset_link']); ?>
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
