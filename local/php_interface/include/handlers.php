<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("search", "BeforeIndex", array('Ex2', 'Ex2_92'));

class Ex2
{
    function Ex2_92($arFields){
        if(!CModule::IncludeModule("iblock")) // подключаем модуль
            return $arFields;
        $iblockId = CIBlockElement::GetIBlockByID($arFields['ITEM_ID']);
        if($arFields["MODULE_ID"] == "iblock" && $iblockId == NEWS_IBLOCK_ID)
        {
            $arFields["TITLE"] = mb_substr($arFields["BODY"], 0, 50)."...";
        }
        return $arFields;
    }
}