<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$eventHandler = Bitrix\Main\EventManager::getInstance();
$eventHandler->addEventHandler('main', 'OnBeforeEventAdd', array('Ex2', 'Ex2_51'));

class Ex2
{
    function Ex2_51($event, $lid, $arFields){
        if ($event == 'FEEDBACK_FORM'){
            global $USER;
            if ($USER->isAuthorized()){
                $arFields['AUTHOR'] = Loc::getMessage('EX2_51_AUTH_USER', array(
                    "#ID#" => $USER->GetID(),
                    "#LOGIN#" => $USER->GetLogin(),
                    "#NAME#" => $USER->GetFullName(),
                    "#NAME_FORM#" => $arFields['AUTHOR'],
                ));
            } else {
                $arFields['AUTHOR'] = Loc::getMessage('NO_EX2_51_AUTH_USER', array(
                    "#NAME_FORM#" => $arFields['AUTHOR']
                ));
            }

            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => Loc::getMessage('EX2_51_REPLACEMENT'),
                "MODULE_ID" => "main",
                "ITEM_ID" => $event,
                "DESCRIPTION" => Loc::getMessage('EX2_51_REPLACEMENT') . ' - ' . $arFields['AUTHOR'],
            ));
        }
    }
}