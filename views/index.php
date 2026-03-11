<?php
$pageTitle = 'Cafeteria | Home';
require __DIR__ . '/layout/header.php';
$activePage = 'home';
require __DIR__ . '/layout/user-header.php';
?>

<main class="mx-auto w-full max-w-6xl px-6 py-10">
  <?php if (isset($_SESSION['order_success'])): ?>
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      <?= htmlspecialchars($_SESSION['order_success'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php unset($_SESSION['order_success']); ?>
  <?php endif; ?>

  <div class="grid gap-8 lg:grid-cols-[1.05fr_1.6fr]" data-cart-scope>
    <section class="rounded-3xl bg-white/90 p-6 shadow-lg shadow-orange-100" data-cart>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Current Order</h2>
        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-brand-700">Room Selection</span>
      </div>

      <form method="post" action="<?= url('/order-confirm'); ?>" class="mt-6 space-y-4" data-validate-form data-require-cart>
        <input type="hidden" name="cart_data" id="cart_data" value="" />
        <div class="space-y-4" data-cart-items></div>

        <div>
          <label class="text-sm font-semibold text-slate-700">Notes</label>
          <textarea
            name="notes"
            class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100"
            rows="3" placeholder="e.g. Extra sugar"></textarea>
        </div>

        <div data-field>
          <label class="text-sm font-semibold text-slate-700">Room</label>
          <select required name="room"
            class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100">
            <option value="">Select a room</option>
            <option>2010</option>
            <option>2011</option>
            <option>2012</option>
            <option>3010</option>
            <option>3011</option>
          </select>
          <p class="mt-1 hidden text-xs text-red-600" data-error></p>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600 hidden" data-form-alert>
          Please fix the highlighted fields.
        </div>

        <div class="mt-2 flex items-center justify-between border-t border-orange-100 pt-4 text-base font-semibold">
          <span>Total</span>
          <span class="text-brand-700" data-cart-total>EGP 0</span>
        </div>

        <button
          class="mt-2 w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:bg-brand-700"
          type="submit">
          Confirm Order
        </button>
      </form>
    </section>

    <section data-menu>
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Menu</p>
          <h2 class="text-2xl font-semibold">Pick your favorites</h2>
        </div>
        <div class="flex items-center gap-2 rounded-full border border-orange-100 bg-white px-4 py-2 text-sm">
          <span>🔍</span>
          <input class="w-40 bg-transparent text-sm focus:outline-none" placeholder="Search products..." />
        </div>
      </div>

      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php if (!empty($products) && is_array($products)): ?>
          <?php foreach ($products as $product): ?>
            <div
              class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
              data-product data-id="<?= htmlspecialchars($product['id']); ?>"
              data-name="<?= htmlspecialchars($product['name']); ?>"
              data-price="<?= htmlspecialchars($product['price']); ?>">
              <?php if (!empty($product['product_picture'])):
                $picturePath = $product['product_picture'];
                if (!str_starts_with($picturePath, '/')) {
                  $picturePath = '/' . $picturePath;
                }
                $pictureUrl = url($picturePath);
                ?>
                <img src="<?= htmlspecialchars($pictureUrl); ?>" alt="<?= htmlspecialchars($product['name']); ?>"
                  class="mx-auto h-20 w-20 object-cover rounded-xl" />
              <?php else: ?>
                <div class="text-3xl text-center">🛍️</div>
              <?php endif; ?>
              <p class="mt-3 font-semibold"><?= htmlspecialchars($product['name']); ?></p>
              <?php if (!empty($product['category_name'])): ?>
                <p class="text-xs text-slate-400">Category: <?= htmlspecialchars($product['category_name']); ?></p>
              <?php endif; ?>
              <div class="mt-2 flex items-center justify-between">
                <p class="text-sm text-slate-500"><?= number_format((float) $product['price'], 2); ?> LE</p>
                <button type="button"
                  class="rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white hover:bg-brand-700"
                  data-action="add">Add</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full rounded-3xl border border-orange-100 bg-white p-6 text-center text-slate-600">
            No available products found.
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
</main>

<?php
$inlineScript = <<<'JS'
      // Page-specific JS
      (() => {
        const scope = document.querySelector('[data-cart-scope]');
        if (!scope) return;

        const currency = (value) => `EGP ${value}`;
        const cartRoot = scope.querySelector('[data-cart]');
        const menuRoot = scope.querySelector('[data-menu]');
        if (!cartRoot || !menuRoot) return;

        const cartItemsEl = cartRoot.querySelector('[data-cart-items]');
        const cartTotalEl = cartRoot.querySelector('[data-cart-total]');
        const notesEl = cartRoot.querySelector('textarea');
        const roomEl = cartRoot.querySelector('select');

        const products = Array.from(menuRoot.querySelectorAll('[data-product]')).map((card) => ({
          id: card.getAttribute('data-id'),
          name: card.getAttribute('data-name'),
          price: parseFloat(card.getAttribute('data-price') || '0'),
        }));

        const STORAGE_CART_KEY = 'cafeteria_cart';
        const STORAGE_NOTE_KEY = 'cafeteria_note';
        const STORAGE_ROOM_KEY = 'cafeteria_room';
        const SHOULD_CLEAR_STORAGE = %s;

        const cart = new Map();

        const loadCart = () => {
          try {
            const saved = JSON.parse(sessionStorage.getItem(STORAGE_CART_KEY) || '[]');
            return new Map(saved.map((item) => [item.id, item]));
          } catch (err) {
            console.warn('Could not load cart from sessionStorage', err);
            return new Map();
          }
        };

        const saveCart = () => {
          const items = Array.from(cart.values());
          sessionStorage.setItem(STORAGE_CART_KEY, JSON.stringify(items));
        };

        const saveNote = () => {
          if (notesEl) {
            sessionStorage.setItem(STORAGE_NOTE_KEY, notesEl.value.trim());
          }
        };

        const saveRoom = () => {
          if (roomEl) {
            sessionStorage.setItem(STORAGE_ROOM_KEY, roomEl.value);
          }
        };

        const syncItemNotes = () => {
          if (!notesEl) return;

          const note = notesEl.value.trim();
          cart.forEach((item) => {
            item.note = note;
          });
        };

        const cartInitial = loadCart();
        cartInitial.forEach((item, id) => cart.set(id, item));

        if (SHOULD_CLEAR_STORAGE) {
          sessionStorage.removeItem(STORAGE_CART_KEY);
          sessionStorage.removeItem(STORAGE_NOTE_KEY);
          sessionStorage.removeItem(STORAGE_ROOM_KEY);
          cart.clear();
        }

        if (notesEl) {
          const savedNote = sessionStorage.getItem(STORAGE_NOTE_KEY);
          if (savedNote !== null) {
            notesEl.value = savedNote;
          }
        }

        if (roomEl) {
          const savedRoom = sessionStorage.getItem(STORAGE_ROOM_KEY);
          if (savedRoom !== null) {
            roomEl.value = savedRoom;
          }
        }

        const renderCart = () => {
          if (!cartItemsEl) return;
          cartItemsEl.innerHTML = '';

          if (!cart.size) {
            const empty = document.createElement('p');
            empty.className = 'text-sm text-slate-500 text-center py-6';
            empty.textContent = 'No items yet. Select a product.';
            cartItemsEl.appendChild(empty);
          }

          // Header line for clarity
          const header = document.createElement('div');
          header.className = 'flex items-center justify-between pb-2 border-b border-orange-100 font-semibold';
          header.innerHTML = '<span>Product</span><span>Qty</span><span>Subtotal</span>';
          cartItemsEl.appendChild(header);

          let total = 0;
          cart.forEach((item) => {
            total += item.price * item.qty;

            const row = document.createElement('div');
            row.className = 'flex items-center justify-between rounded-2xl border border-orange-100 bg-white px-4 py-3';
            row.setAttribute('data-cart-item', item.id);

            row.innerHTML = `
              <div>
                <p class="font-medium">${item.name}</p>
                <p class="text-xs text-slate-500">${item.note || ''}</p>
              </div>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1 text-sm">
                  <button class="text-brand-600" data-action="dec" data-id="${item.id}">−</button>
                  <span>${item.qty}</span>
                  <button class="text-brand-600" data-action="inc" data-id="${item.id}">+</button>
                </div>
                <span class="font-semibold text-slate-700">${currency(item.price * item.qty)}</span>
                <button class="text-xs text-red-500" data-action="remove" data-id="${item.id}">✕</button>
              </div>
            `;

            cartItemsEl.appendChild(row);
          });

          if (cartTotalEl) {
            cartTotalEl.textContent = currency(total);
          }
        };

        const updateCart = (product, delta = 1) => {
          if (!product) return;
          const existing = cart.get(product.id) || { ...product, qty: 0 };
          existing.qty += delta;
          existing.note = notesEl ? notesEl.value.trim() : (existing.note || '');

          if (existing.qty <= 0) {
            cart.delete(product.id);
          } else {
            cart.set(product.id, existing);
          }

          saveCart();
          renderCart();
        };

        menuRoot.addEventListener('click', (event) => {
          const actionBtn = event.target.closest('[data-action="add"]');
          if (actionBtn) {
            const card = actionBtn.closest('[data-product]');
            if (!card) return;

            const productId = card.getAttribute('data-id');
            const product = products.find((item) => item.id === productId);
            updateCart(product, 1);
            return;
          }

          const card = event.target.closest('[data-product]');
          if (!card) return;

          const productId = card.getAttribute('data-id');
          const product = products.find((item) => item.id === productId);
          updateCart(product, 1);
        });

        cartRoot.addEventListener('click', (event) => {
          const actionBtn = event.target.closest('[data-action]');
          if (!actionBtn) return;

          const id = actionBtn.getAttribute('data-id');
          const product = products.find((item) => item.id === id) || cart.get(id);
          if (!product) return;

          const action = actionBtn.getAttribute('data-action');
          if (action === 'inc') updateCart(product, 1);
          if (action === 'dec') updateCart(product, -1);
          if (action === 'remove') {
            cart.delete(id);
            saveCart();
          }
          renderCart();
        });

        if (notesEl) {
          notesEl.addEventListener('input', () => {
            syncItemNotes();
            saveNote();
            saveCart();
          });

          notesEl.addEventListener('blur', () => {
            syncItemNotes();
            saveNote();
            saveCart();
          });
        }

        if (roomEl) {
          roomEl.addEventListener('change', () => {
            roomEl.classList.remove('border-red-500');
            saveRoom();
          });
        }

        const orderForm = scope.querySelector('form[data-validate-form]');
        if (orderForm) {
          orderForm.addEventListener('submit', (event) => {
            if (!cart.size) {
              event.preventDefault();
              alert('Your cart is empty. Add products first.');
              return;
            }

            syncItemNotes();

            const payload = {
              items: Array.from(cart.values()),
              note: notesEl ? notesEl.value.trim() : '',
              room: roomEl ? roomEl.value : '',
              total: Array.from(cart.values()).reduce((sum, item) => sum + item.price * item.qty, 0),
              created_at: new Date().toISOString(),
            };
            const hiddenInput = document.getElementById('cart_data');
            if (hiddenInput) {
              hiddenInput.value = JSON.stringify(payload);
            }

            saveCart();
            saveNote();
            saveRoom();
          });
        }

        renderCart();
      })();
JS;
$inlineScript = sprintf($inlineScript, isset($_SESSION['clear_order_storage']) ? 'true' : 'false');
unset($_SESSION['clear_order_storage']);
require __DIR__ . '/layout/footer.php';
?>
