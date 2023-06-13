<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable;

if(!Loader::includeModule("iblock"))
{
    ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
    return;
}

// Устанавливаем дефолтные значения на случай, если не установлены в параметрах
if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

if (!isset($arParams["PRODUCTS_IBLOCK_ID"]))
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;

if (!isset($arParams["CLASSIF_IBLOCK_ID"]))
    $arParams["CLASSIF_IBLOCK_ID"] = 0;


if ($this->StartResultCache()){
    $arResult = array();

    // Массив разделов классификатора
    $arClassif = array(); // Массив разделов
    $arClassifIds = array(); // Массив id разделов

    $obClassifSection = CIBlockSection::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["CLASSIF_IBLOCK_ID"],
            "ACTIVE" => "Y",
        ),
        false,
        array( // select - свойства из задания
            "ID",
            "NAME",
        ),
        false
    );

    while ($classifSection = $obClassifSection->GetNext()) {
        $arClassifIds[] = $classifSection["ID"];
        $arClassif[$classifSection["ID"]] = $classifSection;
    }

    // Список активных разделов с привязкой к активным раздела классификатора
    $arSections = array(); // Массив разделов с идентификаторами в качестве ключей
    $arSectionsID = array(); // Массив id разделов для фильтра товаров

    $obSection = CIBlockSection::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"], // Обязателен в фильтре для вывода свойств UF_***
            "ACTIVE" => "Y",
            $arParams["CLASSIF_LINK_PROPERTY"] => $arClassifIds
        ),
        false,
        array( // select - свойства из задания
            "ID",
            "IBLOCK_ID",
            "NAME",
            $arParams["CLASSIF_LINK_PROPERTY"]
        ),
        false
    );

    while ($arSection = $obSection->GetNext()) {
        $arSectionsID[] = $arSection["ID"];
        $arSections[$arSection["ID"]] = $arSection;
        /* Распределяем разделы по классификатору */
        /* Так как свойство не множественное, просто по id */
        $classId = $arSection[$arParams["CLASSIF_LINK_PROPERTY"]];
        $arClassif[$classId]['SECTIONS'][$arSection['ID']] = $arSection['NAME'];
    }

    // Список активных товаров из разелов
    $arProducts = array();
    $obProduct = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
            "ACTIVE" => "Y",
            "SECTION_ID" => $arSectionsID
        ),
        false,
        false,
        array( // select - свойства из задания плюс IBLOCK_ID и ID для корректного вывода значений свойств
            "IBLOCK_ID",
            "ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "PROPERTY_ARTNUMBER",
            "PROPERTY_MATERIAL",
            "PROPERTY_PRICE",
            "DETAIL_PAGE_URL",
        )
    );

    while ($arProduct = $obProduct->GetNext()) {
        $arProducts[] = $arProduct;
        /* Распределяем товары по классификатору */
        foreach ($arClassif as $key => $classif){
            /* Если id раздела текущего товара есть в списке разделов классификатора */
            if (array_key_exists($arProduct["IBLOCK_SECTION_ID"], $classif["SECTIONS"])){
                /* то добавляем товар к этому классификатору */
                $arClassif[$key]["PRODUCTS"][] = $arProduct;
            }
        }
    }

    $arResult['CLASSIF_CNT'] = count($arClassif);

    $arResult['ITEMS'] = $arClassif;
    $this->SetResultCacheKeys(array('CLASSIF_CNT'));

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SIMPLECOMP_EXAM2_CLASSIF_COUNT') . $arResult['CLASSIF_CNT']);