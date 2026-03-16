<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  <?php view('partials/products-grid.php', compact('products', 'searchTerm')); ?>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
  <div class="mt-6 flex flex-wrap items-center justify-center gap-2 text-xs" data-pagination>
    <?php
    $query = [];
    if (!empty($searchTerm)) {
      $query['search'] = $searchTerm;
    }
    ?>

    <?php if (($currentPage ?? 1) > 1): ?>
      <?php $query['page'] = 1; ?>
      <a
        href="<?= htmlspecialchars(url('/' . (!empty($query) ? '?' . http_build_query($query) : '')), ENT_QUOTES, 'UTF-8'); ?>"
        data-page-link
        class="rounded-full border border-orange-200 px-3 py-1 text-slate-600 transition hover:border-brand-500 hover:text-brand-700">
        First
      </a>
      <?php $query['page'] = $currentPage - 1; ?>
      <a
        href="<?= htmlspecialchars(url('/' . (!empty($query) ? '?' . http_build_query($query) : '')), ENT_QUOTES, 'UTF-8'); ?>"
        data-page-link
        class="rounded-full border border-orange-200 px-3 py-1 text-slate-600 transition hover:border-brand-500 hover:text-brand-700">
        Prev
      </a>
    <?php endif; ?>

    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
      <?php $query['page'] = $page; ?>
      <a
        href="<?= htmlspecialchars(url('/' . (!empty($query) ? '?' . http_build_query($query) : '')), ENT_QUOTES, 'UTF-8'); ?>"
        data-page-link
        class="rounded-full px-3 py-1 transition <?= $page === ($currentPage ?? 1) ? 'bg-brand-600 text-white' : 'border border-orange-200 text-slate-600 hover:border-brand-500 hover:text-brand-700'; ?>">
        <?= $page; ?>
      </a>
    <?php endfor; ?>

    <?php if (($currentPage ?? 1) < $totalPages): ?>
      <?php $query['page'] = $currentPage + 1; ?>
      <a
        href="<?= htmlspecialchars(url('/' . (!empty($query) ? '?' . http_build_query($query) : '')), ENT_QUOTES, 'UTF-8'); ?>"
        data-page-link
        class="rounded-full border border-orange-200 px-3 py-1 text-slate-600 transition hover:border-brand-500 hover:text-brand-700">
        Next
      </a>
      <?php $query['page'] = $totalPages; ?>
      <a
        href="<?= htmlspecialchars(url('/' . (!empty($query) ? '?' . http_build_query($query) : '')), ENT_QUOTES, 'UTF-8'); ?>"
        data-page-link
        class="rounded-full border border-orange-200 px-3 py-1 text-slate-600 transition hover:border-brand-500 hover:text-brand-700">
        Last
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>
