<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define('NEWS_IBLOCK_ID', 1);

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("Ex2", "Ex2_75"));

class Ex2
{
    function Ex2_75(&$arFields){
        if($arFields["IBLOCK_ID"] == NEWS_IBLOCK_ID)
        {
            if(strpos($arFields["PREVIEW_TEXT"], "калейдоскоп" ) !== false)
            {
                $arFields["PREVIEW_TEXT"] = str_replace("калейдоскоп", "[...]", $arFields["PREVIEW_TEXT"]);

                CEventLog::Add(array(
                        "SEVERITY" => "INFO",
                        "AUDIT_TYPE_ID" => "Замена описания",
                        "MODULE_ID" => "iblock",
                        "ITEM_ID" => $arFields['ID'],
                        "DESCRIPTION" => "Замена слова калейдоскоп на [...], в новости с ID = ".$arFields["ID"]
                    )
                );
            }
        }
    }
}