<?php
$pageTitle = 'Cafeteria | Edit User';
require __DIR__ . '/layout/header.php';
$activePage = 'users';
require __DIR__ . '/layout/admin-header.php';
$formAction = url('/admin/users/update');
$formHeading = 'Edit User';
$submitLabel = 'Update';
$pageDescription = 'User';
$isEdit = true;
?>

<?php require __DIR__ . '/partials/user-form.php'; ?>

<?php
$inlineScript = <<<'JS'
      // Page-specific JS
      (() => {
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
