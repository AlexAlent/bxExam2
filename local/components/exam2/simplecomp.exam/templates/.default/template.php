<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    ---
    <br />
    <p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<?php if (count($arResult["ITEMS"]) > 0): ?>
    <ul>
        <?php foreach ($arResult["ITEMS"] as $arNews): ?>
            <li>
                <b>
                    <?= $arNews["NAME"]; ?>
                </b>
                - <?= $arNews["ACTIVE_FROM"]; ?>
                (<?= implode(", ", $arNews["SECTIONS"]); ?>)
            </li>

            <?php if (count($arNews["PRODUCTS"]) > 0): ?>
                <ul>
                    <?php foreach ($arNews["PRODUCTS"] as $arProduct): ?>
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
<?php endif; ?>