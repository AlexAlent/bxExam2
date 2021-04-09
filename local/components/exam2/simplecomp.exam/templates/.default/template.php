<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
    ---
    <br />
    <p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<?php if (count($arResult["CLASSIF"]) > 0): ?>
    <ul>
        <?php foreach ($arResult["CLASSIF"] as $arClassificator): ?>
            <li>
                <b>
                    <?=$arClassificator["NAME"];?>
                </b>
                <?php if (count($arClassificator["ELEMENTS"]) > 0): ?>
                    <ul>
                        <?php foreach ($arClassificator["ELEMENTS"] as $arItems): ?>
                            <li>
                                <?=$arItems["NAME"];?> -
                                <?=$arItems["PROPERTY"]["PRICE"]["VALUE"];?> -
                                <?=$arItems["PROPERTY"]["MATERIAL"]["VALUE"];?> -
                                <?=$arItems["PROPERTY"]["ARTNUMBER"]["VALUE"];?> -
                                <a href="<?=$arItems["DETAIL_PAGE_URL"];?>"><?= Loc::getMessage('SIMPLECOMP_EXAM2_DETAIL_PAGE_URL'); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>