<?php
if (!defined("BASE_PATH")) { header("Location: /"); exit; }
$activePage = $activePage ?? '';
$brandSubtitle = 'Admin Console';
$currentUser = $_SESSION['name'] ?? 'Admin';
$bp = BASE_PATH;
$navigationItems = [
    ['href' => $bp . '/admin/orders',       'label' => 'Orders',       'active' => $activePage === 'orders'],
    ['href' => $bp . '/admin/products',     'label' => 'Products',     'active' => $activePage === 'products'],
    ['href' => $bp . '/admin/users',        'label' => 'Users',        'active' => $activePage === 'users'],
    ['href' => $bp . '/admin/manual-order', 'label' => 'Manual Order', 'active' => $activePage === 'manual-order'],
    ['href' => $bp . '/admin/checks',       'label' => 'Checks',       'active' => $activePage === 'checks'],
    ['href' => $bp . '/logout',             'label' => 'Logout',       'active' => false],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4';

require __DIR__ . '/navbar.php';
