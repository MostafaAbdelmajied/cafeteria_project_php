<?php
$activePage = $activePage ?? '';
$brandSubtitle = 'Admin Console';
//$currentUser = $currentUser ?? 'Admin';
$navigationItems = [
    ['href' => url('/admin'), 'label' => 'Home', 'active' => $activePage === 'home'],
    ['href' => url('/admin/products'), 'label' => 'Products', 'active' => $activePage === 'products'],
    ['href' => url('/admin/users'), 'label' => 'Users', 'active' => $activePage === 'users'],
    ['href' => url('/admin-manual-order'), 'label' => 'Manual Order', 'active' => $activePage === 'manual-order'],
    ['href' => url('/admin/checks'), 'label' => 'Checks', 'active' => $activePage === 'checks'],
    ['href' => url('/admin/orders'), 'label' => 'Orders', 'active' => $activePage === 'orders'],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4';

require __DIR__ . '/navbar.php';
