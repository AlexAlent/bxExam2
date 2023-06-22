<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application,
    \Bitrix\Main\Loader;

$request = Application::getInstance()->getContext()->getRequest();
?>

<?php if (intval($request['element_id']) > 0) {

    Loader::includeModule('iblock');
    $itemId = $request['element_id'];
    $rsItem = CIBlockElement::GetByID($itemId);
    $arItem = $rsItem->Fetch();

    if ($request['ajax'] == 'Y'){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        die(CUtil::PhpToJSObject($arItem));
    } else {
        ?>
            <script>
                BX.ready(function (){
                    BX.adjust(
                        BX('answer_area').querySelector('span'),
                        {
                            html: '<?=$arItem['TIMESTAMP_X']?>',
                        }
                    );
                });
            </script>
        <?php
    }

}