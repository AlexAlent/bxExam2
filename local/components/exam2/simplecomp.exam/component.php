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

$arParams["EXAM2_AUTHOR_PROPERTY"] = trim($arParams["EXAM2_AUTHOR_PROPERTY"]);

$arParams["PROPERTY_AUTHOR_TYPE_UF"] = trim($arParams["PROPERTY_AUTHOR_TYPE_UF"]);

global $USER;
if (!$USER->IsAuthorized()) {
    return false;
}
$currentUserId = $USER->GetID();

if ($this->StartResultCache(false , array($currentUserId))) {

    $rsUser = CUser::GetByID($currentUserId);
    $arUser = $rsUser->Fetch();
    $currentUserType = $arUser[$arParams["PROPERTY_AUTHOR_TYPE_UF"]];

    // Получаем пользователей, того же типа, как у текущего, включая текущего
    $userList = array();
    $by = 'id';
    $order = 'asc';
    $rsUsers = CUser::GetList(
        $by,
        $order,
        array(
            $arParams["PROPERTY_AUTHOR_TYPE_UF"] => $currentUserType,
        ),
        array(
            "SELECT" => array("LOGIN", "ID")
        )
    );
    while($arUser = $rsUsers->GetNext()) {
        $userList[$arUser["ID"]] = array("LOGIN" => $arUser["LOGIN"]);
    }
    $userListId = array_keys($userList);

    // Получаем список новостей, у которых есть привязка к выбранным пользователям
    $arNewsList = array(); // Список новостей
    $rsNews = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "PROPERTY_".$arParams["EXAM2_AUTHOR_PROPERTY"] => $userListId,
        ),
        false,
        false,
        array(
            "NAME",
            "ACTIVE_FROM",
            "ID",
            "IBLOCK_ID",
            "PROPERTY_".$arParams["EXAM2_AUTHOR_PROPERTY"]
        )
    );

    $arNewsId = array();
    while($arNews = $rsNews->GetNext()) {
        // Собираем массив уникальных новостей
        if (!isset($arNewsList[$arNews["ID"]])) {
            $arNewsList[$arNews["ID"]] = $arNews;
        }
        // У каждой новости определим массив идентификаторов привязанных к ней авторов
        $arNewsList[$arNews["ID"]]["AUTHORS"][] = $arNews["PROPERTY_".$arParams["EXAM2_AUTHOR_PROPERTY"]."_VALUE"];
    }

    foreach ($arNewsList as $curNew) {
        /* Не выводим новости текущего автора */
        if (in_array($currentUserId, $curNew["AUTHORS"])){
            continue;
        }
        /* Распределяем новости по авторам */
        foreach ($curNew["AUTHORS"] as $authorId) {
            $userList[$authorId]["NEWS"][] = $curNew;
            $arNewsId[$curNew["ID"]] = $curNew["ID"];
        }
    }

    unset($userList[$currentUserId]);

    $arResult["AUTHORS"] = $userList;
    $arResult["COUNT"] = count($arNewsId);
    $this->SetResultCacheKeys(array("COUNT"));
    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage("SIMPLECOMP_EXAM2_COUNT") . $arResult["COUNT"]);