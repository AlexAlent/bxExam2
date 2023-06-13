<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    ---
    <br />
    <p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<?php if (count($arResult["ITEMS"]) > 0): ?>
    <ul>
        <?php foreach ($arResult["ITEMS"] as $arClassif): ?>
            <li>
                <b>
                    <?= $arClassif["NAME"]; ?>
                </b>
                - (<?= implode(", ", $arClassif["SECTIONS"]); ?>)
            </li>

            <?php if (count($arClassif["PRODUCTS"]) > 0): ?>
                <ul>
                    <?php foreach ($arClassif["PRODUCTS"] as $arProduct): ?>
                        <li>
                            <?= $arProduct["NAME"]; ?>
                            <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>">
                                (<?= $arProduct["DETAIL_PAGE_URL"]; ?>)</a> -
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