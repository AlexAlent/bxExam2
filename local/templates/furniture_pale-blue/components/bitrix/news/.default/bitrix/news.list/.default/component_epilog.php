<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['SET_SPECIALDATE_PROPERTY'] == 'Y' && $arResult['FIRST_NEWS_DATE']){
    $firstNewsDate = $arResult['FIRST_NEWS_DATE'];
    $APPLICATION->SetPageProperty('specialdate', $firstNewsDate);
}