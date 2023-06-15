<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(!isset($arParams["PRODUCTS_IBLOCK_ID"]))
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;

if(!isset($arParams["CLASSIF_IBLOCK_ID"]))
    $arParams["CLASSIF_IBLOCK_ID"] = 0;

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["CLASSIF_LINK_CODE"] = trim($arParams["CLASSIF_LINK_CODE"]);

if($this->startResultCache())
{
    $arResult = array();

    //iblock sections
    $arSelectSect = array (
        "ID",
        "IBLOCK_ID",
        "NAME",
    );
    $arFilterSect = array (
        "IBLOCK_ID" => $arParams["CLASSIF_IBLOCK_ID"],
        "ACTIVE" => "Y"
    );
    $arSortSect = array ();

    $arResult["CLASSIFICATOR"] = array();
    $rsSections = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect, false);
    while($arSection = $rsSections->GetNext())
    {
        $arResult["CLASSIFICATOR"][$arSection['ID']] = $arSection;
    }
    $arSectionsIds = array_keys($arResult["CLASSIFICATOR"]);

	
	//iblock elements
	$arSelectElems = array (
		"ID",
		"IBLOCK_ID",
		"NAME",
		"PROPERTY_PRICE",
		"PROPERTY_MATERIAL",
		"PROPERTY_ARTNUMBER",
		"PROPERTY_".$arParams["CLASSIF_LINK_CODE"],
	);
	$arFilterElems = array (
		"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
		"ACTIVE" => "Y",
        "PROPERTY_".$arParams["CLASSIF_LINK_CODE"] => $arSectionsIds,
	);
	$arSortElems = array ();
	
	$arResult["ELEMENTS"] = array();
	$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext())
	{
        $classifId = $arElement["PROPERTY_".$arParams["CLASSIF_LINK_CODE"]."_VALUE"];
        /* Так как для каждого привязанного раздела отдельный элемент выборки */
        $arResult['CLASSIFICATOR'][$classifId]['PRODUCTS'][] = $arElement;
        $arResult["ELEMENTS"][$arElement['ID']] = $arElement['NAME']; // Для счетчика
	}


    $arResult['CLASSIF_CNT'] = count($arResult["ELEMENTS"]);

    $this->SetResultCacheKeys(array('CLASSIF_CNT'));

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SIMPLECOMP_EXAM2_CLASSIF_COUNT') . $arResult['CLASSIF_CNT']);