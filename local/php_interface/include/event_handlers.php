<?php
IncludeModuleLangFile(__FILE__);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("CIBlockHandler", "OnBeforeIBlockElementUpdateHandler"));

class CIBlockHandler
{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    function OnBeforeIBlockElementUpdateHandler($arFields)
    {
        if ($arFields['IBLOCK_ID'] == CATALOG_IBLOCK_ID){
            if ($arFields['ACTIVE'] == 'N'){

                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "SHOW_COUNTER");
                $arFilter = Array("IBLOCK_ID"=>CATALOG_IBLOCK_ID, "ID"=>$arFields["ID"]);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                $arFields = $res->Fetch();
                $showCounter = $arFields['SHOW_COUNTER'];
                if ($showCounter > MAX_COUNT){
                    global $APPLICATION;
                    $messageText = GetMessage('IMPOSIBLE_DEACTIVATE_MESSAGE', array("#COUNT#" => $showCounter));
                    $APPLICATION->ThrowException($messageText);
                    return false;
                }
            }
        }
    }
}