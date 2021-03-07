<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['SET_SPECIALDATE_PROPERTY'] == 'Y'){
    $firstNewsDate = $arResult['ITEMS'][0]['DISPLAY_ACTIVE_FROM'] ? $arResult['ITEMS'][0]['DISPLAY_ACTIVE_FROM'] : false;
    $arResult['FIRST_NEWS_DATE'] = $firstNewsDate;
    $ob_componenet = $this->GetComponent();
    $ob_componenet->SetResultCacheKeys(array('FIRST_NEWS_DATE'));
}