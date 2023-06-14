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

if (empty($arParams["NEWS_IBLOCK_ID"]))
    $arParams["NEWS_IBLOCK_ID"] = 0;

$arParams["USER_LINK_CODE"] = trim($arParams["USER_LINK_CODE"]);

global $USER;
if (!$USER->IsAuthorized()) {
    return false;
}
$currentUserId = $USER->GetID();

if ($this->StartResultCache(false , array($currentUserId))) {
    $arResult = array();
    $favorites = array();

    // iblock elements.
    $arUsers = array();
    $arSelectElems = array (
        "ID",
        "IBLOCK_ID",
        "NAME",
        "PROPERTY_PRICE",
        "PROPERTY_MATERIAL",
        "PROPERTY_ARTNUMBER",
        "PROPERTY_".$arParams["USER_LINK_CODE"],
    );
    $arFilterElems = array (
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE" => "Y",
        "!=PROPERTY_".$arParams["USER_LINK_CODE"] => false, // с непустым значением свойства
    );
    $arSortElems = array ();

    $arResult["ELEMENTS"] = array(); // Для подсчета
    $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
    while($arElement = $rsElements->GetNext())
    {
        $arResult["ELEMENTS"][] = $arElement;
        /* Так как GetNext для каждой привязки выдает отдельный результат */
        $userId = intval($arElement["PROPERTY_".$arParams["USER_LINK_CODE"]."_VALUE"]);
        $arUsers[$userId]['PRODUCTS'][$arElement['ID']] = $arElement;
        /* Избранное */
        $favorites[$arElement['ID']][] = $userId;
    }
    $arUsersIds = array_keys($arUsers);


    // users
    $arOrderUser = array("id");
    $sortOrder = "asc";
    $arFilterUser = array(
        "ACTIVE" => "Y",
    );
    $arParams = array(
        "FIELDS" => array('ID', 'LOGIN'),
    );

    $arResult["USERS"] = array();
    $rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, $arParams); // выбираем пользователей
    while($arUser = $rsUsers->GetNext())
    {
        /* В фильтре массив не работает, поэтому перебираем здесь */
        if (in_array($arUser['ID'], $arUsersIds)){
            $arUsers[$arUser["ID"]]['LOGIN'] = $arUser['LOGIN'];
            /* Добавляем товары */
            $arResult["USERS"][$arUser["ID"]]['PRODUCTS'] = $arUsers[$arUser["ID"]]['PRODUCTS'];
        }
    }

    /* Отделяем текущего пользователя от остальных */
    $arResult['CURRENT_USER'] = $arResult["USERS"][$currentUserId];
    unset($arResult["USERS"][$currentUserId]);

    /* Фильтруем товары */
    foreach ($arResult['USERS'] as $userId => &$user){
        /* Второй список – избранные товары других пользователей, у которых есть хотя бы один общий товар в избранном с текущим пользователем. */
        $arIntersect = array_intersect_key($user['PRODUCTS'], $arResult['CURRENT_USER']['PRODUCTS']);
        if (count($arIntersect) < 1){
            unset($arResult['USERS'][$userId]);
            continue;
        }
        foreach ($user['PRODUCTS'] as $productId => &$product){
            /* При построении второго списка нужно исключить товары, уже присутствующие в первом списке. */
            if (array_key_exists($productId, $arResult['CURRENT_USER']['PRODUCTS'])){
                unset($user['PRODUCTS'][$productId]);
            }
            /* Во втором списке возле каждого товара выводится список логинов пользователей, у которых он находится в избранном. */
            foreach ($favorites[$productId] as $userId){
                $product['USERS_ARRAY'][] = $arUsers[$userId]['LOGIN'];
            }
            $product['USERS'] = implode(', ', $product['USERS_ARRAY']);
        }
    }

    $arResult["FAVORITE_COUNT"] = count($arResult["ELEMENTS"]);
    $this->SetResultCacheKeys(array("FAVORITE_COUNT"));
    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage("SIMPLECOMP_EXAM2_COUNT") . $arResult["FAVORITE_COUNT"]);