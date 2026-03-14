<?php
$pageTitle = 'Cafeteria | Order Confirmation';
require __DIR__ . '/layout/header.php';
$activePage = 'home';
require __DIR__ . '/layout/user-header.php';

$pending = (isset($pendingOrder) && is_array($pendingOrder)) ? $pendingOrder : [];
?>

<main class="mx-auto w-full max-w-4xl px-6 py-10">
    <div class="rounded-3xl bg-white/90 p-8 shadow-lg shadow-orange-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Review</p>
                <h1 class="text-2xl font-semibold">Confirm Your Order</h1>
            </div>
            <a href="<?= url('/'); ?>" class="text-sm text-brand-600 hover:underline">Back to menu</a>
        </div>

        <?php if (isset($_SESSION['order_error'])): ?>
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?= htmlspecialchars($_SESSION['order_error'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['order_error']); ?>
        <?php endif; ?>

        <?php if (empty($pending) || empty($pending['items']) || !is_array($pending['items'])): ?>
            <div class="p-6 rounded-2xl border border-orange-100 bg-orange-50 text-orange-900">
                No order in progress. Please add items in the menu first.
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white p-4">
                    <div class="text-sm font-semibold text-slate-500">Items</div>
                    <div class="mt-3 space-y-2">
                        <?php foreach ($pending['items'] as $item): ?>
                            <?php $subtotal = number_format($item['price'] * $item['qty'], 2); ?>
                            <div class="flex items-center justify-between border-b border-orange-100 pb-2">
                                <span class="text-sm font-medium"><?= htmlspecialchars($item['name']); ?></span>
                                <span class="text-sm text-slate-600">x<?= htmlspecialchars($item['qty']); ?></span>
                                <span class="text-sm font-semibold">EGP <?= $subtotal; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-orange-100 bg-white p-4">
                        <p class="text-xs text-slate-500">Notes</p>
                        <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($pending['note'] ?? '-'); ?></p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-white p-4">
                        <p class="text-xs text-slate-500">Room</p>
                        <p class="mt-1 text-sm text-slate-700"><?= htmlspecialchars($pending['room'] ?? '-'); ?></p>
                    </div>
                </div>

                <div class="rounded-2xl border border-orange-100 bg-white p-4">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span>Total</span>
                        <span class="font-semibold">EGP <?= number_format($pending['total'] ?? 0, 2); ?></span>
                    </div>
                </div>

                <form method="post" action="<?= url('/order-submit'); ?>" class="flex flex-wrap gap-3">
                    <button class="rounded-2xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700"
                        type="submit">Confirm Order</button>
                </form>
                <form method="post" action="<?= url('/order-cancel'); ?>" class="flex flex-wrap gap-3">
                    <button class="rounded-2xl border border-red-200 px-6 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                        type="submit">Cancel Order</button>
                    <a href="<?= url('/'); ?>"
                        class="rounded-2xl border border-orange-200 px-6 py-3 text-sm font-semibold text-slate-600">Back to
                        Cart</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
require __DIR__ . '/layout/footer.php';
?>
