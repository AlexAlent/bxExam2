<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp.exam",
	"",
	Array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CLASSF_IBLOCK_ID" => "8",
		"LINK_TEMPLATE" => "",
		"PRODUCTS_IBLOCK_ID" => "2",
		"PROPERTY_CODE" => "FIRMA"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>