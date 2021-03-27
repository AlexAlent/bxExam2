<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBuildGlobalMenu', array('Ex2', 'Ex2_95'));

class Ex2
{
    function Ex2_95(&$aGlobalMenu, &$aModuleMenu) {
        $isAdmin = false;
        $isManager = false;

        // Получаем группы текущего пользователя
        global $USER;
        $userGroup = CUser::GetUserGroupList($USER->GetID());
        // Получаем ID группы контент редакторы
        $contentGroupID = CGroup::GetList(
            $by = "c_sort",
            $order = "asc",
            array(
                "STRING_ID" => "content_editor"
            )
        )->Fetch()["ID"];
        // Перебираем группы пользователя
        while ($group = $userGroup -> Fetch()) {

            if ($group["GROUP_ID"] == 1) {
                $isAdmin = true;
            }

            if ($group["GROUP_ID"] == $contentGroupID) {
                $isManager = true;
            }
        }

        $arModuleMenuNew = array();
        // Если пользователь не принадлежит группе администраторы и принадлежит группе контент редакторы
        if (!$isAdmin && $isManager) {

            foreach ($aModuleMenu as $key => $item) {

                if ($item["items_id"] == "menu_iblock_/news") {
                    $aModuleMenu = [$item];

                    foreach ($item["items"] as $childItem) {

                        if ($childItem["items_id"] == "menu_iblock_/news/1") {
                            $aModuleMenu[0]["items"] = [$childItem];
                            break;
                        }
                    }
                    break;
                }
            }
            $aGlobalMenu  = ["global_menu_content" => $aGlobalMenu["global_menu_content"]];
        }
    }
}