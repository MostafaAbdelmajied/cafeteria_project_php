<?php
$activePage = $activePage ?? '';
//$currentUser = $currentUser ?? 'Islam Askar';
$brandSubtitle = 'Order Management';
$navigationItems = [
    ['href' => url('/'), 'label' => 'Home', 'active' => $activePage === 'home'],
    ['href' => url('/my-orders'), 'label' => 'My Orders', 'active' => $activePage === 'my-orders'],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4';

require __DIR__ . '/navbar.php';
