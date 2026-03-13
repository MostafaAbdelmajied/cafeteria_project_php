<?php
$activePage = $activePage ?? '';
$brandSubtitle = 'Admin Console';
//$currentUser = $currentUser ?? 'Admin';
$navigationItems = [
    ['href' => '/admin', 'label' => 'Home', 'active' => $activePage === 'home'],
    ['href' => '/admin/products', 'label' => 'Products', 'active' => $activePage === 'products'],
    ['href' => '/admin/users', 'label' => 'Users', 'active' => $activePage === 'users'],
    ['href' => 'admin-manual-order.php', 'label' => 'Manual Order', 'active' => $activePage === 'manual-order'],
    ['href' => 'admin-checks.php', 'label' => 'Checks', 'active' => $activePage === 'checks'],
    ['href' => '/admin/orders', 'label' => 'Orders', 'active' => $activePage === 'orders'],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4';

require __DIR__ . '/navbar.php';
