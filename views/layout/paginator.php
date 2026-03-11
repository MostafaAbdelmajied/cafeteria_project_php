<?php
$enabledButtonClass = 'border border-orange-200 text-slate-600';
$disabledButtonClass = 'bg-brand-600 text-white';
?>
<div class="mt-6 flex items-center justify-center gap-2 text-xs">
    <a class="rounded-full border border-orange-200 px-3 py-1"
       href="?page=1">«</a>
    <?php if ($currentPage > 1): ?>
        <a class="rounded-full border border-orange-200 px-3 py-1"
           href="?page=<?= $currentPage - 1 ?>">‹</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="rounded-full px-3 py-1 <?= $i === $currentPage ? $disabledButtonClass : $enabledButtonClass ?>"
           href="?page=<?= $i ?>"><?= $i ?> </a>
    <?php endfor; ?>
    <?php if ($currentPage < $totalPages): ?>

        <a class="rounded-full border border-orange-200 px-3 py-1"
           href="?page=<?= $currentPage + 1 ?>">›</a>
    <?php endif; ?>
    <a class="rounded-full border border-orange-200 px-3 py-1"
       href="?page=<?= $totalPages ?>">»</a>
</div>
