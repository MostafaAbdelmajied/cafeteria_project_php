<?php
$product = $product ?? null;
$isEdit = $isEdit ?? false;
$formAction = $formAction ?? url('/admin/products/store');
$formHeading = $formHeading ?? 'Add Product';
$submitLabel = $submitLabel ?? 'Save';
$pageDescription = $pageDescription ?? 'Product';
$productName = $product['name'] ?? '';
$productPrice = $product['price'] ?? '';
$selectedCategoryId = (string)($product['category_id'] ?? '');
$productPicture = $product['product_picture'] ?? '';
?>

<main class="mx-auto w-full max-w-3xl px-6 py-10">
    <div class="rounded-3xl bg-white/90 p-8 shadow-2xl shadow-orange-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600"><?= $pageDescription ?></p>
                <h1 class="text-2xl font-semibold"><?= $formHeading ?></h1>
            </div>
            <a href="<?= url("/admin/products") ?>" class="text-xs font-semibold text-brand-600">Back to products</a>
        </div>

        <form class="mt-6 grid gap-4" data-validate-form action="<?= $formAction ?>"
              method="post" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
            <?php endif; ?>

            <div data-field>
                <label class="text-sm font-medium text-slate-700">Product Name</label>
                <input type="text"
                       name="name"
                       required
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['name']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="e.g. Tea"
                       value="<?= htmlspecialchars($productName) ?>"/>
                <?php if (isset($_SESSION['errors']['name'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['name'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field>
                <label class="text-sm font-medium text-slate-700">Price (EGP)</label>
                <input type="number"
                       name="price"
                       required
                       step="0.01"
                       data-validate="number" data-min="1"
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['price']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="e.g. 3.50"
                       value="<?= htmlspecialchars((string)$productPrice) ?>"/>
                <?php if (isset($_SESSION['errors']['price'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['price'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field>
                <label class="text-sm font-medium text-slate-700">Category</label>
                <select
                        name="category_id"
                        required
                        class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['category_id']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none">
                    <option value="">Choose category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category["id"] ?>" <?= $selectedCategoryId === (string)$category["id"] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category["name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($_SESSION['errors']['category_id'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['category_id'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field>
                <label class="text-sm font-medium text-slate-700">Product Picture</label>
                <input type="file"
                       name="product_image"
                       <?= !$isEdit ? 'required' : '' ?>
                       class="mt-2 w-full rounded-2xl border border-dashed <?= isset($_SESSION['errors']['product_image']) ? 'border-red-500 bg-red-50/50' : 'border-orange-200 bg-orange-50/50' ?> px-4 py-3 text-sm"/>
                <?php if (isset($_SESSION['errors']['product_image'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['product_image'] ?></p>
                <?php endif; ?>
                <?php if ($productPicture !== ''): ?>
                    <div class="mt-3 flex items-center gap-3 rounded-2xl bg-orange-50 px-3 py-3">
                        <img src="<?= url('/' . $productPicture) ?>" alt="<?= htmlspecialchars($productName) ?>"
                             class="h-14 w-14 rounded-xl object-cover">
                        <p class="text-xs text-slate-500">Current image will be kept unless you upload a replacement.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600"
                     data-form-alert>
                    Please fix the highlighted fields.
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php else: ?>
                <div class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600"
                     data-form-alert>
                    Please fix the highlighted fields.
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-2xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white">
                    <?= $submitLabel ?>
                </button>
                <button type="reset"
                        class="rounded-2xl border border-orange-200 px-6 py-3 text-sm font-semibold text-slate-600">
                    Reset
                </button>
            </div>
        </form>
    </div>
</main>
