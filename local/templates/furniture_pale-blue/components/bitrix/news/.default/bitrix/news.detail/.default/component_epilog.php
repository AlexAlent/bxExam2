<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (isset($arResult['CANONICAL_LINK'])){
    $canonicalLink = $arResult['CANONICAL_LINK'];
    $APPLICATION->SetPageProperty('canonical', $canonicalLink);
}