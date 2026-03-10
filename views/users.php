<?php

require_once("layout/header.php")
?>
<body>
    <?php foreach ($user as $u): ?>
        <h1><?= $u ?></h1>
    <?php endforeach; ?>


    <a href="<?= url('/users') ?>">users</a>
</body>
</html>