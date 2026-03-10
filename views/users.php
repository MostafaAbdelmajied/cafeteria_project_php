<?php
$pageTitle = 'Cafeteria | Users';
require __DIR__ . '/layout/header.php';
?>
    <?php
    foreach ($user as $u): ?>
        <h1><?= $u ?></h1>
    <?php endforeach; ?>


    <a href="<?= url('/users') ?>">users</a>
<?php require __DIR__ . '/layout/footer.php'; ?>
