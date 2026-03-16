<?php
$pageTitle = 'Cafeteria | Add User';
require __DIR__ . '/layout/header.php';
$activePage = 'users';
require __DIR__ . '/layout/admin-header.php';
$formAction = url('/admin/users/store');
$formHeading = 'Add User';
$submitLabel = 'Save';
$pageDescription = 'User';
$isEdit = false;
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
