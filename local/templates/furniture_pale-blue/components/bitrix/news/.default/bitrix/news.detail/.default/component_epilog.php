<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
define('REPORT_IBLOCK_ID', 6);
global $USER;
global $APPLICATION;
?>

<?php
if ($request['report'] == 'Y' && intval($request['new_id']) > 0){
    $newId = intval($request['new_id']);
    $name = session_id() . '_' . $newId;

    /* Проверка на случай если пользователь уже жаловался */
    $res = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => REPORT_IBLOCK_ID, 'ACTIVE' => 'Y', 'NAME' => $name),
            false,
            array("nTopCount" => 1),
            array('ID')
    );
    /* Если пользователь уже жаловался */
    if (intval($res->SelectedRowsCount()) > 0){
        $result = 'Уже жаловалcя';
    } else {
        /* Добавляем жалобу */
        if ($USER->IsAuthorized()){
            $userName = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
        } else {
            $userName = 'Не авторизован';
        }

        $element = new CIBlockElement();
        $arFields = array(
                'IBLOCK_ID' => REPORT_IBLOCK_ID,
                'NAME' => $name,
                'ACTIVE_FROM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
                'PROPERTY_VALUES' => array(
                        'USER' => $userName,
                        'NEWS' => $newId,
                ),
        );
        if ($elId = $element->Add($arFields)) {
            $result = 'Ваше мнение учтено, №' . $elId;
        } else {
            $result = 'Ошибка!';
        }

    }

    if (isset($request['ajax'])){
        $APPLICATION->RestartBuffer();
        die($result);
    } else {
        ?>
        <script>
            BX.adjust(
                BX('report_result'),
                {
                    html: '<?=$result?>',
                    style: {display: 'block'}
                }
            );
        </script>
        <?php
    }

}
