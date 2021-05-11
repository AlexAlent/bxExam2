<?
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
// Добавляем кнопку в выпадающее меню
global $USER;
if ($USER->IsAuthorized()) {
    $arButtons = CIBlock::GetPanelButtons($arParams["NEWS_IBLOCK_ID"]);
    $this->AddIncludeAreaIcons(
        array(
            array(
                "ID" => "linklb",
                "TITLE" => GetMessage("IB_IN_ADMIN"),
                "URL" => $arButtons["submenu"]["element_list"]["ACTION_URL"],
                "IN_PARAMS_MENU" => true, // Показать в контекстном меню
            )
        )
    );
}
if ($USER->IsAuthorized()) {
    $by = 'id';
    $order = 'asc';
    $currentUserId = $USER->GetID();
    $currentUser = Cuser::GetList(
        $by,
        $order,
        array("ID" => $currentUserId),
        array("SELECT" => array($arParams["PROPERTY_AUTHOR_TYPE_UF"]))
    )->Fetch();
    $currentUserType = $currentUser[$arParams["PROPERTY_AUTHOR_TYPE_UF"]];

    if ($this->StartResultCache(false , array($currentUserType, $currentUserId))) {

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
        while($arUser = $rsUsers->Fetch()) {
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
        while($arNews = $rsNews->Fetch()) {
            // Собираем массив уникальных новостей
            if (empty($arNewsList[$arNews["ID"]])) {
                $arNewsList[$arNews["ID"]] = $arNews;
            }
            // У каждой новости определим массив идентификаторов привязанных к ней авторов
            $arNewsList[$arNews["ID"]]["AUTHORS"][] = $arNews["PROPERTY_".$arParams["EXAM2_AUTHOR_PROPERTY"]."_VALUE"];
        }

        foreach ($arNewsList as $curNew) {
            if (in_array($currentUserId, $curNew["AUTHORS"])){
                continue;
            }
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
    } else {
        $this->abortResultCache();
    }

    $APPLICATION->SetTitle(GetMessage("SIMPLECOMP_EXAM2_COUNT") . $arResult["COUNT"]);
}