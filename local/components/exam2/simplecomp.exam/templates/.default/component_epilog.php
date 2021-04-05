<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (isset($arResult['MIN_PRICE']) && isset($arResult['MAX_PRICE'])){
    $text = Loc::getMessage('MIN_MAX_PRICE',
        array('#MIN_PRICE#' => $arResult['MIN_PRICE'],
        '#MAX_PRICE#' => $arResult['MAX_PRICE'],
        ));
    $APPLICATION->AddViewContent("prices", $text);
}