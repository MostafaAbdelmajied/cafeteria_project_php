<?php if (!empty($products) && is_array($products)): ?>
  <?php foreach ($products as $product): ?>
    <div
      class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
      data-product
      data-id="<?= htmlspecialchars($product['id']); ?>"
      data-name="<?= htmlspecialchars($product['name']); ?>"
      data-price="<?= htmlspecialchars($product['price']); ?>">
      <?php if (!empty($product['product_picture'])):
        $picturePath = $product['product_picture'];
        if (!str_starts_with($picturePath, '/')) {
          $picturePath = '/' . $picturePath;
        }
        $pictureUrl = url($picturePath);
        ?>
        <img
          src="<?= htmlspecialchars($pictureUrl); ?>"
          alt="<?= htmlspecialchars($product['name']); ?>"
          class="mx-auto h-20 w-20 rounded-xl object-cover" />
      <?php else: ?>
        <div class="text-center text-3xl">Item</div>
      <?php endif; ?>
      <p class="mt-3 font-semibold"><?= htmlspecialchars($product['name']); ?></p>
      <?php if (!empty($product['category_name'])): ?>
        <p class="text-xs text-slate-400">Category: <?= htmlspecialchars($product['category_name']); ?></p>
      <?php endif; ?>
      <div class="mt-2 flex items-center justify-between">
        <p class="text-sm text-slate-500"><?= number_format((float) $product['price'], 2); ?> LE</p>
        <button
          type="button"
          class="rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white hover:bg-brand-700"
          data-action="add">Add</button>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="col-span-full rounded-3xl border border-orange-100 bg-white p-6 text-center text-slate-600">
    <?= !empty($searchTerm) ? 'No products match your search.' : 'No available products found.'; ?>
  </div>
<?php endif; ?>
