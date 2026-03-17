<?php

use Src\Classes\Auth;

$brandSubtitle = $brandSubtitle ?? 'Order Management';
$currentUser = $currentUser ?? Auth::user()['name'] ?? null;
$navigationItems = $navigationItems ?? [];
$headerContainerClass = $headerContainerClass ?? 'mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4';
?>
<header class="border-b border-orange-100 bg-white/70 backdrop-blur">
  <div class="<?= htmlspecialchars($headerContainerClass, ENT_QUOTES, 'UTF-8') ?>">
    <div class="flex w-full items-center justify-center gap-3 sm:w-auto sm:justify-start">
      <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-500 text-xl text-white">☕</div>
      <div>
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">Cafeteria</p>
        <p class="text-xs text-slate-500"><?= htmlspecialchars($brandSubtitle, ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    </div>
    <nav class="flex w-full flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm font-medium sm:w-auto">
      <?php foreach ($navigationItems as $item): ?>
        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= !empty($item['active']) ? 'text-brand-700' : 'text-slate-600 hover:text-brand-600' ?>">
          <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <?php if ($currentUser): ?>
      <div class="flex w-full flex-wrap items-center justify-center gap-3 text-sm sm:w-auto sm:justify-end">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100">👤</span>
        <span class="text-center font-medium"><?= htmlspecialchars($currentUser, ENT_QUOTES, 'UTF-8') ?></span>

        <a href="<?= url('/logout') ?>" class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700 transition hover:bg-orange-200 sm:ml-4">
          Logout
        </a>
      </div>
    <?php endif; ?>
  </div>
</header>
