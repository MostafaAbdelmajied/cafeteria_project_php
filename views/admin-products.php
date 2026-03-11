<?php
$pageTitle = 'Cafeteria | Admin Products';
require __DIR__ . '/layout/header.php';
$activePage = 'products';
require __DIR__ . '/layout/admin-header.php';
?>

<main class="mx-auto w-full max-w-6xl px-6 py-10">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Catalog</p>
            <h1 class="text-2xl font-semibold">All Products</h1>
        </div>
        <a href="admin-add-product.php" class="rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white">Add
            Product</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
        <table class="w-full text-left text-sm">
            <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-6 py-4">Product</th>
                <th class="px-6 py-4">Price</th>
                <th class="px-6 py-4">Image</th>
                <th class="px-6 py-4">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-orange-100">
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-6 py-4"><?= $product["name"] ?></td>
                    <td class="px-6 py-4"><?= $product["price"] ?> EGP</td>
                    <td class="px-6 py-4 text-lg"><?= $product["product_picture"] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <button class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                available
                            </button>
                            <button class="rounded-full border border-orange-200 px-3 py-1 text-xs">edit</button>
                            <button class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">
                                delete
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    require_once "views/layout/paginator.php"
    ?>
</main>

<?php
$inlineScript = <<<'JS'
      // Page-specific JS
      (() => {
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
