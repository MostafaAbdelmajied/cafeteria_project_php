<?php
$pageTitle = 'Cafeteria | Admin Users';
require __DIR__ . '/layout/header.php';
$activePage = 'users';
require __DIR__ . '/layout/admin-header.php';
?>

<main class="mx-auto w-full max-w-6xl px-6 py-10">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Directory</p>
            <h1 class="text-2xl font-semibold">All Users</h1>
        </div>
        <a href="<?= url('/admin/users/create') ?>"
           class="rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white">Add
            User</a>
    </div>

    <?php if (isset($_SESSION['errors']['general'])): ?>
        <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <?= htmlspecialchars($_SESSION['errors']['general']) ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="mt-6 overflow-hidden rounded-3xl border border-orange-100 bg-white/90 shadow-lg shadow-orange-100">
        <table class="w-full text-left text-sm">
            <thead class="bg-orange-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-6 py-4">Name</th>
                <th class="px-6 py-4">Room</th>
                <th class="px-6 py-4">Image</th>
                <th class="px-6 py-4">Ext.</th>
                <th class="px-6 py-4">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-orange-100">
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td class="px-6 py-4"><?= $user["name"] ?></td>
                    <td class="px-6 py-4"><?= $user["room_no"] ?></td>
                    <td class="px-6 py-4 text-lg">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?= url('/' . $user["profile_picture"]) ?>"
                                 alt="<?= $user["name"] ?>" class="h-10 w-10 rounded-lg object-cover">
                        <?php else: ?>
                            <span class="text-xs text-slate-400 italic">No image</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?= $user["ext"] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="<?= url('/admin/users/edit?id=' . $user['id']) ?>"
                               class="rounded-full border border-orange-200 px-3 py-1 text-xs font-semibold text-orange-600 transition-all hover:bg-orange-500 hover:text-white">edit</a>
                            <form action="<?= url('/admin/users/delete') ?>" method="post" style="display: inline"
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="id" value="<?= (int)$user["id"] ?>">
                                <button type="submit"
                                        class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600 transition-all hover:border-red-600 hover:bg-transparent border border-transparent">
                                    delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    require __DIR__ . '/layout/paginator.php'
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
