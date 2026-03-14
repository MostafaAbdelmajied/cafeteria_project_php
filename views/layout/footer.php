<?php
if (!defined("BASE_PATH")) {
    header("Location: /");
    exit;
}
$inlineScript = isset($inlineScript) ? $inlineScript : null;
$includeAppJs = isset($includeAppJs) ? $includeAppJs : true;
?>
<?php if ($inlineScript): ?>
    <script>
        <?= $inlineScript . "\n" ?>
    </script>
<?php endif; ?>
<?php if ($includeAppJs): ?>
    <script src="<?= BASE_PATH ?>/public/js/app.js"></script>
<?php endif; ?>
</body>

</html>