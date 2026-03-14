<?php
if (!defined("BASE_PATH")) { header("Location: /"); exit; }
$activePage = $activePage ?? '';
$currentUser = $_SESSION['name'] ?? 'User';
$brandSubtitle = 'Order Management';
$bp = BASE_PATH;
$navigationItems = [
    ['href' => $bp . '/home',      'label' => 'Home',      'active' => $activePage === 'home'],
    ['href' => $bp . '/my-orders', 'label' => 'My Orders', 'active' => $activePage === 'my-orders'],
    ['href' => $bp . '/logout',    'label' => 'Logout',    'active' => false],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4';

require __DIR__ . '/navbar.php';
