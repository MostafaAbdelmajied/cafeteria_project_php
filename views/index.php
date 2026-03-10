<?php
$pageTitle = 'Cafeteria | Home';
require __DIR__ . '/layout/header.php';
$activePage = 'home';
require __DIR__ . '/layout/user-header.php';
?>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
      <div class="grid gap-8 lg:grid-cols-[1.05fr_1.6fr]" data-cart-scope>
        <section class="rounded-3xl bg-white/90 p-6 shadow-lg shadow-orange-100" data-cart>
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Current Order</h2>
            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-brand-700">Room Selection</span>
          </div>

          <form class="mt-6 space-y-4" data-validate-form data-require-cart>
            <div class="space-y-4" data-cart-items></div>

            <div>
              <label class="text-sm font-semibold text-slate-700">Notes</label>
              <textarea class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100" rows="3" placeholder="e.g. Extra sugar"></textarea>
            </div>

            <div data-field>
              <label class="text-sm font-semibold text-slate-700">Room</label>
              <select required class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 p-3 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-orange-100">
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

            <button class="mt-2 w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:bg-brand-700" type="submit">
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
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="1" data-name="Tea" data-price="5" data-icon="🍵">
              <div class="text-3xl">🍵</div>
              <p class="mt-3 font-semibold">Tea</p>
              <p class="text-sm text-slate-500">5 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="2" data-name="Coffee" data-price="6" data-icon="☕">
              <div class="text-3xl">☕</div>
              <p class="mt-3 font-semibold">Coffee</p>
              <p class="text-sm text-slate-500">6 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="3" data-name="Cola" data-price="10" data-icon="🥤">
              <div class="text-3xl">🥤</div>
              <p class="mt-3 font-semibold">Cola</p>
              <p class="text-sm text-slate-500">10 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="4" data-name="Juice" data-price="12" data-icon="🧃">
              <div class="text-3xl">🧃</div>
              <p class="mt-3 font-semibold">Juice</p>
              <p class="text-sm text-slate-500">12 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="5" data-name="Water" data-price="3" data-icon="💧">
              <div class="text-3xl">💧</div>
              <p class="mt-3 font-semibold">Water</p>
              <p class="text-sm text-slate-500">3 LE</p>
            </div>
            <div class="rounded-3xl border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-product data-id="6" data-name="Hot Choco" data-price="15" data-icon="🍫">
              <div class="text-3xl">🍫</div>
              <p class="mt-3 font-semibold">Hot Choco</p>
              <p class="text-sm text-slate-500">15 LE</p>
            </div>
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

        const cart = new Map();

        const renderCart = () => {
          if (!cartItemsEl) return;
          cartItemsEl.innerHTML = '';

          if (!cart.size) {
            const empty = document.createElement('p');
            empty.className = 'text-sm text-slate-500 text-center py-6';
            empty.textContent = 'No items yet. Select a product.';
            cartItemsEl.appendChild(empty);
          }

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

          if (existing.qty <= 0) {
            cart.delete(product.id);
          } else {
            cart.set(product.id, existing);
          }

          renderCart();
        };

        menuRoot.addEventListener('click', (event) => {
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
          if (action === 'remove') cart.delete(id);
          renderCart();
        });

        if (notesEl) {
          notesEl.addEventListener('blur', () => {
            cart.forEach((item) => {
              item.note = notesEl.value.trim();
            });
          });
        }

        if (roomEl) {
          roomEl.addEventListener('change', () => roomEl.classList.remove('border-red-500'));
        }

        renderCart();
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
