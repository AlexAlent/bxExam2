<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define('NEWS_IBLOCK_ID', 1);
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Ex2", "Ex2_74"));

class Ex2
{
    function Ex2_74(&$arFields){
        if($arFields["IBLOCK_ID"] == NEWS_IBLOCK_ID)
        {
            if(strpos($arFields["PREVIEW_TEXT"], Loc::getMessage('EX2_NEEDLE_WORD') ) !== false)
            {
                global $APPLICATION;
                $APPLICATION->ThrowException(Loc::getMessage('EX2_ERROR_TEXT'));
                return false;
            }
        }
    }
}