<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

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


if ($this->startResultCache()){
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
            $arParams["PRODUCTS_IBLOCK_ID_PROPERTY"] => $arNewsID
        ),
        false,
        array( // select - свойства из задания
            "ID",
            "NAME",
            $arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]
        ),
        false
    );

    while ($arSectionCatalog = $obSection->Fetch()) {
        $arSectionsID[] = $arSectionCatalog["ID"];
        $arSections[$arSectionCatalog["ID"]] = $arSectionCatalog;
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
    }

    $arResult['PRODUCTS_CNT'] = count($arProducts);

    // Распределяем товары по новостям
    foreach ($arProducts as $arCurProduct){
        // Так как каждый товар дефолтно привязан только к одному разделу
        // В разделе текущего товара перебираем массив идентификаторов привязянных новостей
        foreach ($arSections[$arCurProduct["IBLOCK_SECTION_ID"]][$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $newsId) {
            if (isset($arNews[$newsId])){ // Если новость с нужным id в принципе существует
                $arNews[$newsId]["PRODUCTS"][] = $arCurProduct;
            }
        }
    }

    // Распределяем разделы по новостям
    foreach ($arSections as $arSection) {
        // В каждом разделе перебираем массив идентификаторов привязянных новостей
        foreach ($arSection[$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $newId) {
            if (isset($arNews[$newId]))
                $arNews[$newId]['SECTIONS'][] = $arSection["NAME"];
        }
    }

    $arResult['ITEMS'] = $arNews;
    $this->SetResultCacheKeys(array('PRODUCTS_CNT'));

    $this->includeComponentTemplate();
} else {
    $this->abortResultCache();
}

$APPLICATION->SetTitle(GetMessage('SIMPLECOMP_EXAM2_PRODUCTS_COUNT') . $arResult['PRODUCTS_CNT']);
?>