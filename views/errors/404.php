<?php
$pageTitle = 'Cafeteria | Not Found';
require __DIR__ . '/../layout/header.php';
?>
    <main class="flex min-h-screen items-center justify-center px-6">
      <div class="max-w-md rounded-3xl bg-white/90 p-10 text-center shadow-2xl shadow-orange-100">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-600 text-3xl text-white">☕</div>
        <h1 class="mt-6 text-4xl font-semibold">404</h1>
        <p class="mt-2 text-sm text-slate-500">Oops! Page not found.</p>
        <a href="index.php" class="mt-6 inline-flex rounded-full bg-brand-600 px-5 py-2 text-xs font-semibold text-white">Return to Home</a>
      </div>
    </main>
<?php require __DIR__ . '/../layout/footer.php'; ?>
