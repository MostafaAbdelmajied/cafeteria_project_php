<?php
$pageTitle = 'Cafeteria | Home';
$savedNote = (string) ($_SESSION['cafeteria_note'] ?? '');
$savedRoom = (string) ($_SESSION['cafeteria_room'] ?? '');
require __DIR__ . '/layout/header.php';
$activePage = 'home';
require __DIR__ . '/layout/user-header.php';
?>

<main class="mx-auto w-full max-w-6xl px-6 py-10">
  <?php if (isset($_SESSION['order_error'])): ?>
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
      <?= htmlspecialchars($_SESSION['order_error'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php unset($_SESSION['order_error']); ?>
  <?php endif; ?>

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
            rows="3"
            placeholder="e.g. Extra sugar"><?= htmlspecialchars($savedNote, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div data-field>
          <label class="text-sm font-semibold text-slate-700">Room</label>
          <select required name="room"
            class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100">
            <option value="">Select a room</option>
            <?php foreach (($rooms ?? []) as $room): ?>
              <option value="<?= htmlspecialchars($room, ENT_QUOTES, 'UTF-8'); ?>" <?= $savedRoom === (string) $room ? 'selected' : ''; ?>>
                <?= htmlspecialchars($room, ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
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
        <form method="get" action="<?= url('/'); ?>" class="flex items-center gap-2 rounded-full border border-orange-100 bg-white px-4 py-2 text-sm" data-search-form>
          <span>Search</span>
          <input
            name="search"
            value="<?= htmlspecialchars($searchTerm ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            class="w-40 bg-transparent text-sm focus:outline-none"
            placeholder="Search products..." />
          <button type="submit" class="rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white hover:bg-brand-700">
            Go
          </button>
          <?php if (!empty($searchTerm)): ?>
            <a href="<?= url('/'); ?>" class="text-xs font-medium text-slate-500 hover:text-brand-600">Clear</a>
          <?php endif; ?>
        </form>
      </div>

      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-products-grid data-search-url="<?= htmlspecialchars(url('/'), ENT_QUOTES, 'UTF-8'); ?>">
        <?php view('partials/products-grid.php', compact('products', 'searchTerm')); ?>
      </div>
    </section>
  </div>
</main>

<?php
$inlineScript = <<<'JS'
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
        const searchForm = menuRoot.querySelector('[data-search-form]');
        const searchInput = searchForm ? searchForm.querySelector('input[name="search"]') : null;
        const productsGrid = menuRoot.querySelector('[data-products-grid]');

        const getProducts = () => Array.from(menuRoot.querySelectorAll('[data-product]')).map((card) => ({
          id: card.getAttribute('data-id'),
          name: card.getAttribute('data-name'),
          price: parseFloat(card.getAttribute('data-price') || '0'),
        }));

        const STORAGE_CART_KEY = 'cafeteria_cart';
        const STORAGE_NOTE_KEY = 'cafeteria_note';
        const STORAGE_ROOM_KEY = 'cafeteria_room';
        const SHOULD_CLEAR_STORAGE = %s;

        const cart = new Map();
        let searchTimeout = null;
        let activeSearchController = null;

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

        if (notesEl && !notesEl.value) {
          const savedNote = sessionStorage.getItem(STORAGE_NOTE_KEY);
          if (savedNote !== null) {
            notesEl.value = savedNote;
          }
        }

        if (roomEl && !roomEl.value) {
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
            empty.className = 'py-6 text-center text-sm text-slate-500';
            empty.textContent = 'No items yet. Select a product.';
            cartItemsEl.appendChild(empty);
          }

          const header = document.createElement('div');
          header.className = 'flex items-center justify-between border-b border-orange-100 pb-2 font-semibold';
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
                  <button type="button" class="text-brand-600" data-action="dec" data-id="${item.id}">-</button>
                  <span>${item.qty}</span>
                  <button type="button" class="text-brand-600" data-action="inc" data-id="${item.id}">+</button>
                </div>
                <span class="font-semibold text-slate-700">${currency(item.price * item.qty)}</span>
                <button type="button" class="text-xs text-red-500" data-action="remove" data-id="${item.id}">x</button>
              </div>
            `;

            cartItemsEl.appendChild(row);
          });

          if (cartTotalEl) {
            cartTotalEl.textContent = currency(total);
          }
        };

        const fetchProducts = async (searchTerm, pushState = true) => {
          if (!productsGrid || !searchForm) return;

          if (activeSearchController) {
            activeSearchController.abort();
          }

          activeSearchController = new AbortController();

          const baseUrl = productsGrid.getAttribute('data-search-url') || window.location.pathname;
          const url = new URL(baseUrl, window.location.origin);
          if (searchTerm) {
            url.searchParams.set('search', searchTerm);
          }
          url.searchParams.set('partial', 'products');

          try {
            const response = await fetch(url.toString(), {
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
              },
              signal: activeSearchController.signal,
            });

            if (!response.ok) {
              return;
            }

            productsGrid.innerHTML = await response.text();

            if (pushState) {
              const browserUrl = new URL(baseUrl, window.location.origin);
              if (searchTerm) {
                browserUrl.searchParams.set('search', searchTerm);
              }
              window.history.pushState({ search: searchTerm }, '', browserUrl.toString());
            }
          } catch (error) {
            if (error.name !== 'AbortError') {
              console.error('Search request failed', error);
            }
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
          const products = getProducts();
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

          const products = getProducts();
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

        if (searchForm && searchInput) {
          searchForm.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchProducts(searchInput.value.trim());
          });

          searchInput.addEventListener('input', () => {
            window.clearTimeout(searchTimeout);
            searchTimeout = window.setTimeout(() => {
              fetchProducts(searchInput.value.trim());
            }, 250);
          });

          window.addEventListener('popstate', () => {
            const currentSearch = new URL(window.location.href).searchParams.get('search') || '';
            searchInput.value = currentSearch;
            fetchProducts(currentSearch, false);
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
