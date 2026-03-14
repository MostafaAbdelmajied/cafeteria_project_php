<?php
$inlineScript = $inlineScript ?? null;
$includeAppJs = $includeAppJs ?? true;
?>
<?php if ($inlineScript): ?>
    <script>
<?= $inlineScript . "\n" ?>
    </script>
<?php endif; ?>
<?php if ($includeAppJs): ?>
    <script src="<?= url('/views/assets/app.js') ?>"></script>
<?php endif; ?>
  </body>
</html>
