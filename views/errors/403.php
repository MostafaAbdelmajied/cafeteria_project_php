<?php

use Src\Classes\Auth;

$pageTitle = 'Cafeteria | Access Denied';
$showDebug = filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOL);

$exceptionClass = isset($exception) ? get_class($exception) : null;
$exceptionMessage = isset($exception) ? $exception->getMessage() : null;
$exceptionFile = isset($exception) ? $exception->getFile() : null;
$exceptionLine = isset($exception) ? $exception->getLine() : null;

$homeUrl = Auth::check()
    ? (Auth::user()['is_admin'] ? url('/admin') : url('/'))
    : url('/login');

require __DIR__ . '/../layout/header.php';
?>
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
      <div class="w-full max-w-3xl overflow-hidden rounded-3xl bg-white/90 shadow-2xl shadow-orange-100 ring-1 ring-orange-100">
        <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-brand-700 px-8 py-10 text-white">
          <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl font-semibold">!</div>
          <p class="mt-6 text-sm font-semibold uppercase tracking-[0.3em] text-orange-100">403 Forbidden</p>
          <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">You do not have permission to access this page.</h1>
          <p class="mt-3 max-w-2xl text-sm text-slate-100 sm:text-base">
            Your account is signed in, but this action is restricted. Return to an allowed page or sign in with an account that has the required permissions.
          </p>
        </div>

        <div class="px-8 py-8">
          <div class="rounded-2xl border border-orange-100 bg-orange-50/70 p-5 text-sm leading-6 text-slate-600">
            This page is shown by the global exception handler when a permission check throws a <span class="font-mono text-slate-800">PermissionDeniedException</span>.
          </div>

          <div class="mt-8 flex items-center justify-center gap-3">
            <a href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>" class="inline-flex rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
              Go to Allowed Area
            </a>
            
          </div>

<?php if ($showDebug && isset($exception)): ?>
          <section class="mt-8 rounded-2xl border border-red-100 bg-red-50 p-5">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-red-700">Debug Details</h2>
            <dl class="mt-4 space-y-3 text-sm text-slate-700">
              <div>
                <dt class="font-semibold text-slate-900">Exception</dt>
                <dd class="mt-1 font-mono"><?= htmlspecialchars($exceptionClass ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></dd>
              </div>
              <div>
                <dt class="font-semibold text-slate-900">Message</dt>
                <dd class="mt-1 font-mono"><?= htmlspecialchars($exceptionMessage ?: 'No message provided.', ENT_QUOTES, 'UTF-8') ?></dd>
              </div>
              <div>
                <dt class="font-semibold text-slate-900">Location</dt>
                <dd class="mt-1 font-mono">
                  <?= htmlspecialchars(($exceptionFile ?? 'Unknown file') . ':' . ($exceptionLine ?? '?'), ENT_QUOTES, 'UTF-8') ?>
                </dd>
              </div>
            </dl>
          </section>
<?php endif; ?>
        </div>
      </div>
    </main>
<?php require __DIR__ . '/../layout/footer.php'; ?>
