<?php

$eventManager = Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBeforeProlog', array('Ex2', 'Ex2_94'));

class Ex2
{
    function Ex2_94(){
        global $APPLICATION;
        $cur_page = $APPLICATION->GetCurDir();
        if(\Bitrix\Main\Loader::includeModule('iblock')){
            $arFilter = array('IBLOCK_ID' => IBLOCK_META, 'NAME' => $cur_page);
            $arSelect = array('IBLOCK_ID', 'ID', 'PROPERTY_TITLE', 'PROPERTY_DESCRIPTION');
            $ob = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            if($res = $ob->Fetch()){
                $APPLICATION->SetPageProperty('title', $res['PROPERTY_TITLE_VALUE']);
                $APPLICATION->SetPageProperty('description', $res['PROPERTY_DESCRIPTION_VALUE']);
            }
        }
    }
}