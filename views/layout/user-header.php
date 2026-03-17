<?php
$activePage = $activePage ?? '';
//$currentUser = $currentUser ?? 'Islam Askar';
$brandSubtitle = 'Order Management';
$navigationItems = [
    ['href' => url('/'), 'label' => 'Home', 'active' => $activePage === 'home'],
    ['href' => url('/my-orders'), 'label' => 'My Orders', 'active' => $activePage === 'my-orders'],
];
$headerContainerClass = 'mx-auto flex w-full max-w-6xl flex-wrap items-center justify-center gap-4 px-6 py-4 sm:justify-between';

require __DIR__ . '/navbar.php';
