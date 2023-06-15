<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php foreach ($arResult['VARIABLES'] as $variable => $value): ?>
    <?php echo '<p>' . $variable . ' = ' . $value . '</p>'; ?>
<?php endforeach; ?>