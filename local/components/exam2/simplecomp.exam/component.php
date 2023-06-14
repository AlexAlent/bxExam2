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

if (!isset($arParams["NEWS_IBLOCK_ID"]))
    $arParams["NEWS_IBLOCK_ID"] = 0;

global $USER;
if ($USER->IsAuthorized()) {
    $this->AddIncludeAreaIcon(
        array(
            'URL'   => $APPLICATION->GetCurPage() . '?hello=world',
            'TITLE' => GetMessage("HELLO_WORLD"), // Добавить фразу в lang файл
        )
    );

}

if ($this->StartResultCache()){
    $arResult = array();

    // Массив активных новостей
    $arNews = array(); // Массив новостей
    $arNewsID = array(); // Массив id новостей

    $obNews = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "ACTIVE" => "Y"
        ),
        false,
        false,
        array("ID", "NAME", "ACTIVE_FROM"), // select - свойства из задания
    );

    while ($newsElements = $obNews->Fetch()) {
        $arNewsID[] = $newsElements["ID"];
        $arNews[$newsElements["ID"]] = $newsElements;
    }

    // Список активных разделов с привязкой к активным новостям
    $arSections = array(); // Массив разделов с идентификаторами в качестве ключей
    $arSectionsID = array(); // Массив id разделов

    $obSection = CIBlockSection::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"], // Обязателен в фильтре для вывода свойств UF_***
            "ACTIVE" => "Y",
            $arParams["NEWS_LINK_PROPERTY"] => $arNewsID
        ),
        false,
        array( // select - свойства из задания
            "ID",
            "NAME",
            $arParams["NEWS_LINK_PROPERTY"]
        ),
        false
    );

    while ($arSection = $obSection->Fetch()) {
        $arSectionsID[] = $arSection["ID"];
        $arSections[$arSection["ID"]] = $arSection;
        /* Распределяем разделы по новостям */
        /* В каждом разделе перебираем массив идентификаторов привязянных новостей */
        foreach ($arSection[$arParams["NEWS_LINK_PROPERTY"]] as $newId) {
            /* Если новость есть, добавляем раздел в sections с ключом id */
            if (isset($arNews[$newId]))
                $arNews[$newId]["SECTIONS"][$arSection["ID"]] = $arSection["NAME"];
        }
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
        )
    );

    while ($arProduct = $obProduct->Fetch()) {
        $arProducts[] = $arProduct;
        /* Распределяем товары по новостям */
        foreach ($arNews as $new){
            /* Если id раздела текущего товара есть в списке разделов новости */
            if (array_key_exists($arProduct["IBLOCK_SECTION_ID"], $new["SECTIONS"])){
                /* то добавляем товар к этой новости */
                $arNews[$new["ID"]]["PRODUCTS"][] = $arProduct;
            }
        }
    }

    $arResult['PRODUCTS_CNT'] = count($arProducts);

    $arResult['ITEMS'] = $arNews;
    $this->SetResultCacheKeys(array('PRODUCTS_CNT'));

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SIMPLECOMP_EXAM2_PRODUCTS_COUNT') . $arResult['PRODUCTS_CNT']);