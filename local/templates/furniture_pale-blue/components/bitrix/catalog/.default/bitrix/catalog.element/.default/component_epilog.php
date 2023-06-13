<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult["TEXT_FOR_SLOGAN"])){
    $APPLICATION->SetPageProperty("slogan_head", $arResult["TEXT_FOR_SLOGAN"]);
}
