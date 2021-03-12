<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (intval($arParams['CANONICAL_IBLOCK_ID']) > 0){
    $iblockId = intval($arParams['CANONICAL_IBLOCK_ID']);
    $curNewsId = intval($arResult['ID']);

    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_NEW");
    $arFilter = Array("IBLOCK_ID"=>$iblockId, "PROPERTY_NEW.ID"=>$curNewsId, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
    if($ob = $res->GetNextElement()){
        $arFields = $ob->GetFields();
        if ($arFields['PROPERTY_NEW_VALUE'] == $curNewsId && isset($arFields['NAME'])){
            $canonicalValue = $arFields['NAME'];
            $arResult['CANONICAL_LINK'] = $canonicalValue;
            $this->__component->SetResultCacheKeys(array('CANONICAL_LINK'));
        }
    }
}
