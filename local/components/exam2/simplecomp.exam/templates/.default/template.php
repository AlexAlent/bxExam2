<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
    use Bitrix\Main\Localization\Loc;
    Loc::loadMessages(__FILE__);
?>
    ---
    <br />
    <p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<?php if (count($arResult["CLASSIFICATOR"]) > 0): ?>
    <ul>
        <?php foreach ($arResult["CLASSIFICATOR"] as $arClassif): ?>
            <li>
                <b>
                    <?= $arClassif["NAME"]; ?>
                </b>
            </li>

            <?php if (count($arClassif["PRODUCTS"]) > 0): ?>
                <ul>
                    <?php foreach ($arClassif["PRODUCTS"] as $arProduct): ?>
                        <li>
                            <?= $arProduct["NAME"]; ?> -
                            <?= $arProduct["PROPERTY_PRICE_VALUE"]; ?> -
                            <?= $arProduct["PROPERTY_MATERIAL_VALUE"]; ?> -
                            <?= $arProduct["PROPERTY_ARTNUMBER_VALUE"]; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    </br>
    ---

    <p>
        <b><?=Loc::getMessage('SIMPLECOMP_EXAM2_NAVY')?></b>
    </p>
    <?= $arResult['NAV_STRING']; ?>

<?php endif; ?>