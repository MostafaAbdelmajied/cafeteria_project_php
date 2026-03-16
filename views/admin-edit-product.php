<?php
$pageTitle = 'Cafeteria | Edit Product';
require __DIR__ . '/layout/header.php';
$activePage = 'products';
require __DIR__ . '/layout/admin-header.php';
$formAction = url('/admin/products/update');
$formHeading = 'Edit Product';
$submitLabel = 'Update';
$pageDescription = 'Product';
$isEdit = true;
?>

<?php require __DIR__ . '/partials/product-form.php'; ?>

<?php
$inlineScript = <<<'JS'
      // Page-specific JS
      (() => {
      })();
JS;
require __DIR__ . '/layout/footer.php';
?>
