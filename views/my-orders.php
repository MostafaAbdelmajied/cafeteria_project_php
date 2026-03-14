<?php
if (! defined("BASE_PATH")) {
  header("Location: /");
  exit();
}

$pageTitle = "Cafeteria | My Orders";
$activePage = "my-orders";
$orders = $orders ?? [];
$fromDate = $fromDate ?? "";
$toDate = $toDate ?? "";

//csrf token generation to prevent cross-site request forgery attacks
if (empty($_SESSION["csrf_token"])) {
  $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION["csrf_token"];

require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/user-header.php';

// flash message retrieval to display success or error messages to the user
//   and then immediately unset the flash message to prevent it from
//  being displayed again on page refresh

$flash = getFlash();
?>
<!--Display flash message if it exists, with styling based on the type of message (success or error)-->
<?php if ($flash): ?>
  <div class="mx-auto w-full max-w-4xl px-6 mb-4">
    <div class="rounded-2xl border px-4 py-2 text-sm <?= $flash['type'] === 'success'
                                                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                                        : 'border-red-200 bg-red-50 text-red-600' ?>">
      <?= htmlspecialchars($flash['message']) ?>
    </div>
  </div>
<?php endif; ?>
<!--Main content area for displaying the user's order history, with a header and a section for filtering orders by date range-->
<main class="mx-auto w-full max-w-4xl px-6 py-10">

  <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">History</p>
      <h1 class="text-2xl font-semibold">My Orders</h1>
    </div>
    <!--Form for filtering orders by date range, with input fields for "from" and "to" dates,
         and a submit button to apply the filter. If a date filter is applied, a "Clear" button is
         also displayed to allow the user to reset the filter and view all orders again.-->
    <form method="GET" action="<?= BASE_PATH ?>/my-orders" class="flex flex-wrap items-end gap-3">
      <div class="flex flex-col gap-1"></div>
      <label class="text-xs font-medium text-slate-500">From</label>
      <input type="date" name="from" value="<?= htmlspecialchars($fromDate) ?>"
        class="rounded-xl border border-orange-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300">
      <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-slate-500">To</label>
        <input type="date" name="to" value="<?= htmlspecialchars($toDate) ?>"
          class="rounded-xl border border-orange-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300">
      </div>
      <button class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700">
        Filter
      </button>
      <!--show the button only when there is a date filter applied, allowing the user to clear the filter and view all orders again-->
      <?php if ($fromDate || $toDate): ?>
        <a href="<?= BASE_PATH ?>/my-orders"
          class="rounded-xl border border-orange-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-orange-50">
          Clear
        </a>
      <?php endif; ?>
    </form>
  </div>
  <!--If there are no orders to display, show a message prompting the user to place their first order,
     along with a link to the home page where they can browse and place orders. If there are orders,
     display them in a table format with columns for order number, date, total amount,
     status, and actions (such as viewing details or canceling the order if it's still processing).-->
  <?php if (empty($orders)): ?>
    <div class="rounded-3xl border border-orange-100 bg-white/90 px-8 py-16 text-center shadow-lg shadow-orange-100">
      <p class="text-4xl">🧾</p>
      <p class="mt-3 font-semibold text-slate-600">No orders found</p>
      <p class="mt-1 text-sm text-slate-400">
        Place your first order from the
        <a href="<?= BASE_PATH ?>/home" class="text-brand-600 hover:underline">home page</a>.
      </p>
    </div>
    <!-- If there are orders, display them in a table format with columns for order number, date,
         total amount, status, and actions (such as viewing details or canceling the order if it's still
         processing). Each order's status is displayed with a colored badge for easy identification, and
         action buttons allow the user to view more details or cancel the order if it's still in the processing stage. -->
  <?php else: ?>
    <div class="overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
      <table class="w-full text-left text-sm">
        <thead class="bg-orange-50 text-xs uppercase  tracking-wide text-slate-500">
          <tr>
            <th class="px-6 py-3">Id</th>
            <th class="px-6 py-3">Date</th>
            <th class="px-6 py-3">Total</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-orange-100">
          <?php foreach ($orders as $order):
            $orderId = (int) $order['id'];
            $statusColors = [
              'Processing'       => 'bg-amber-100 text-amber-700',
              'Out for delivery' => 'bg-blue-100 text-blue-700',
              'Done'             => 'bg-emerald-100 text-emerald-700',
              'Cancelled'        => 'bg-red-100 text-red-600',
            ];
            $color = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-600';
          ?>
            <tr class="transition hover:bg-orange-50/50">
              <td class="px-6 py-4 font-medium text-slate-700">#<?= $orderId ?></td>
              <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($order['created_at']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($order['room']) ?></td>
              <td class="px-6 py-4 text-right font-semibold"><?= number_format((float)$order['total'], 2) ?> EGP</td>
              <td class="px-6 py-4 text-center">
                <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $color ?>">
                  <?= htmlspecialchars($order['status']) ?>
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <a href="<?= BASE_PATH ?>/order-details?id=<?= $orderId ?>"
                    class="rounded-lg bg-orange-100 px-3 py-1 text-xs font-semibold text-brand-700 transition hover:bg-orange-200">
                    Details
                  </a>
                  <?php if ($order['status'] === 'Processing'): ?>
                    <form method="POST" action="<?= BASE_PATH ?>/order/cancel"
                      onsubmit="return confirm('Cancel this order?')">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <input type="hidden" name="order_id" value="<?= $orderId ?>">
                      <button type="submit"
                        class="rounded-lg bg-red-100 px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-200">
                        Cancel
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</main>

<?php require __DIR__ . '/layout/footer.php'; ?>