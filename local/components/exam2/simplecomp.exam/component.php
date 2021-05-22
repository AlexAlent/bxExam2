<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
    ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
    return;
}

if (empty($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

if (empty($arParams["PRODUCTS_IBLOCK_ID"]))
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;

if (empty($arParams["CLASSF_IBLOCK_ID"]))
    $arParams["CLASSF_IBLOCK_ID"] = 0;

$arParams["PROPERTY_CODE"] = trim($arParams["PROPERTY_CODE"]);
global $USER;

$arNavigation = CDBResult::GetNavParams(false);

if ($this->startResultCache(false, array($USER->GetGroups(), $arNavigation))) {
    $arClassif = array(); //   Список элементов классификатора
    $arClassifId = array(); //   Список идентификаторов
    $arResult["COUNT"] = 0;
    // Получаем список элементов классификатора
    $arSelectElements = array(
        "ID",
        "IBLOCK_ID",
        "NAME",
    );
    $arFilterElements = array(
        "IBLOCK_ID" => $arParams["CLASSF_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
        "ACTIVE" => "Y"
    );
    $arNavStartParams = array(
        "nPageSize" => $arParams["ELEMENT_PER_PAGE"],
        "bShowAll" => true
    );

    $rsClassfElements = CIBlockElement::GetList(array(), $arFilterElements, false, $arNavStartParams, $arSelectElements);

    while($arClassfElement = $rsClassfElements->GetNext()) {
        $arClassif[$arClassfElement["ID"]] = $arClassfElement;
        $arClassifId[] = $arClassfElement["ID"];
    }
    $arResult["COUNT"] = count($arClassifId);

    $arResult["NAV_STRING"] = $rsClassfElements->GetPageNavString(GetMessage("PAGE_TITLE"));

    // Получаем список элементов с привязками к классификатору
    $arSelectElementsCatalog = array (
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "NAME",
        "DETAIL_PAGE_URL",
    );
    $arFilterElementsCatalog = array (
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
        "PROPERTY_".$arParams["PROPERTY_CODE"] => $arClassifId,
        "ACTIVE" => "Y"
    );

    $rsClassifElements = CIBlockElement::GetList(array(), $arFilterElementsCatalog, false, false, $arSelectElementsCatalog);

    while($rsEl= $rsClassifElements->GetNextElement()) {
        $arFields = $rsEl->GetFields();
        $arFields["PROPERTY"] = $rsEl->GetProperties();
        // Перебираем идентификаторы привязанных элементов классификатора
        foreach ($arFields["PROPERTY"]["FIRMA"]["VALUE"] as $value) {
            // Привязываем к элементам классификатора товары
            if (isset($arClassif[$value])){
                $arClassif[$value]["ELEMENTS"][$arFields["ID"]] = $arFields;
            }
        }
    }

    $arResult["CLASSIF"] = $arClassif;
    $this->SetResultCacheKeys(array("COUNT"));
    $this->includeComponentTemplate();
} else {
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("COUNT_SECTIONS") . $arResult["COUNT"]);
?>