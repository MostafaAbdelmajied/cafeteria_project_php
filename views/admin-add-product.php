<?php
$pageTitle = 'Cafeteria | Add Product';
require __DIR__ . '/layout/header.php';
$activePage = 'products';
require __DIR__ . '/layout/admin-header.php';
$formAction = url('/admin/products/store');
$formHeading = 'Add Product';
$submitLabel = 'Save';
$pageDescription = 'Product';
$isEdit = false;
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
