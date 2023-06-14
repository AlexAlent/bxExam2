<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
    <?foreach ($arResult['CURRENT_USER']['PRODUCTS'] as $arProduct):?>
        <li>
            <?= $arProduct["NAME"]; ?> -
            <?= $arProduct["PROPERTY_PRICE_VALUE"]; ?> -
            <?= $arProduct["PROPERTY_MATERIAL_VALUE"]; ?> -
            <?= $arProduct["PROPERTY_ARTNUMBER_VALUE"]; ?>
        </li>
    <?endforeach;?>
</ul>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_ALSO_LIKE")?></b></p>

<? foreach ($arResult['USERS'] as $id => $user): ?>
    <ul>
        <?foreach ($user['PRODUCTS'] as $arProduct):?>
            <li>
                <?= $arProduct["NAME"]; ?> -
                <?= $arProduct["PROPERTY_PRICE_VALUE"]; ?> -
                <?= $arProduct["PROPERTY_MATERIAL_VALUE"]; ?> -
                <?= $arProduct["PROPERTY_ARTNUMBER_VALUE"]; ?> <br />
                В избранном у пользователей: <?= $arProduct["USERS"]; ?>
            </li>
        <? endforeach; ?>
    </ul>
<?endforeach;?>
