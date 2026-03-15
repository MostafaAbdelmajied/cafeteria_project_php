<?php
$user = $user ?? null;
$isEdit = $isEdit ?? false;
$formAction = $formAction ?? url('/admin/users/store');
$formHeading = $formHeading ?? 'Add User';
$submitLabel = $submitLabel ?? 'Save';
$pageDescription = $pageDescription ?? 'User';
$userName = $user['name'] ?? '';
$userEmail = $user['email'] ?? '';
$userRoomNo = $user['room_no'] ?? '';
$userExt = $user['ext'] ?? '';
$userPicture = $user['profile_picture'] ?? '';
$isAdminUser = (string)($user['is_admin'] ?? '0');
?>

<main class="mx-auto w-full max-w-3xl px-6 py-10">
    <div class="rounded-3xl bg-white/90 p-8 shadow-2xl shadow-orange-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600"><?= $pageDescription ?></p>
                <h1 class="text-2xl font-semibold"><?= $formHeading ?></h1>
            </div>
            <a href="<?= url('/admin/users') ?>" class="text-xs font-semibold text-brand-600">Back to users</a>
        </div>

        <form class="mt-6 grid gap-4 sm:grid-cols-2" data-validate-form action="<?= $formAction ?>" method="post" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
            <?php endif; ?>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Name</label>
                <input type="text"
                       name="name"
                       required
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['name']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="Enter name"
                       value="<?= htmlspecialchars($userName) ?>" />
                <?php if (isset($_SESSION['errors']['name'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['name'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Email</label>
                <input type="email"
                       name="email"
                       required
                       data-validate="email"
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['email']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="Enter email"
                       value="<?= htmlspecialchars($userEmail) ?>" />
                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['email'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Password<?= $isEdit ? ' <span class="text-slate-400">(leave blank to keep current)</span>' : '' ?></label>
                <input id="user-password"
                       type="password"
                       name="password"
                       <?= !$isEdit ? 'required' : '' ?>
                       data-validate="min"
                       data-min="6"
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['password']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="<?= $isEdit ? 'Enter new password' : 'Enter password' ?>" />
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['password'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Confirm Password</label>
                <input type="password"
                       name="confirm_password"
                       <?= !$isEdit ? 'required' : '' ?>
                       data-validate="match"
                       data-match="#user-password"
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['confirm_password']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="Confirm password" />
                <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['confirm_password'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Room No.</label>
                <input type="text"
                       name="room_no"
                       required
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['room_no']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="e.g. 2010"
                       value="<?= htmlspecialchars($userRoomNo) ?>" />
                <?php if (isset($_SESSION['errors']['room_no'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['room_no'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Ext.</label>
                <input type="text"
                       name="ext"
                       required
                       class="mt-2 w-full rounded-2xl border <?= isset($_SESSION['errors']['ext']) ? 'border-red-500' : 'border-orange-100' ?> bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none"
                       placeholder="e.g. 5605"
                       value="<?= htmlspecialchars($userExt) ?>" />
                <?php if (isset($_SESSION['errors']['ext'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['ext'] ?></p>
                <?php endif; ?>
            </div>

            <div data-field class="sm:col-span-1">
                <label class="text-sm font-medium text-slate-700">Role</label>
                <select name="is_admin"
                        class="mt-2 w-full rounded-2xl border border-orange-100 bg-white/70 px-4 py-3 text-sm focus:border-brand-300 focus:outline-none">
                    <option value="0" <?= $isAdminUser === '0' ? 'selected' : '' ?>>User</option>
                    <option value="1" <?= $isAdminUser === '1' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="sm:col-span-2" data-field>
                <label class="text-sm font-medium text-slate-700">Profile Picture</label>
                <input type="file"
                       name="profile_picture"
                       <?= !$isEdit ? 'required' : '' ?>
                       class="mt-2 w-full rounded-2xl border border-dashed <?= isset($_SESSION['errors']['profile_picture']) ? 'border-red-500 bg-red-50/50' : 'border-orange-200 bg-orange-50/50' ?> px-4 py-3 text-sm" />
                <?php if (isset($_SESSION['errors']['profile_picture'])): ?>
                    <p class="mt-1 text-xs text-red-600" data-error><?= $_SESSION['errors']['profile_picture'] ?></p>
                <?php endif; ?>
                <?php if ($userPicture !== ''): ?>
                    <div class="mt-3 flex items-center gap-3 rounded-2xl bg-orange-50 px-3 py-3">
                        <img src="<?= url('/' . $userPicture) ?>" alt="<?= htmlspecialchars($userName) ?>" class="h-14 w-14 rounded-xl object-cover">
                        <p class="text-xs text-slate-500">Current image will be kept unless you upload a replacement.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600 sm:col-span-2" data-form-alert>
                    Please fix the highlighted fields.
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php else: ?>
                <div class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-2 text-xs text-red-600 sm:col-span-2" data-form-alert>
                    Please fix the highlighted fields.
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3 sm:col-span-2">
                <button type="submit" class="rounded-2xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white"><?= $submitLabel ?></button>
                <button type="reset" class="rounded-2xl border border-orange-200 px-6 py-3 text-sm font-semibold text-slate-600">Reset</button>
            </div>
        </form>
    </div>
</main>
