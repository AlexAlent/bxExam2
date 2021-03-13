<?php

Bitrix\Main\EventManager::getInstance()->addEventHandler('main', 'OnEpilog', array('Ex2', 'Ex2_93'));

class Ex2
{
    function Ex2_93(){
        if (defined('ERROR_404') && ERROR_404 == 'Y'){
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/404.php';
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';

            CEventLog::Add(
                array(
                    'SEVERITY' => "INFO",
                    'AUDIT_TYPE_ID' => "ERROR_404",
                    'MODULE_ID' => 'main',
                    'DESCRIPTION' => $APPLICATION->GetCurPage()
                )
            );
        }
    }
}