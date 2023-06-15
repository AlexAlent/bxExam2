<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "CLASSIF_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASSIF_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "CLASSIF_LINK_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASSIF_LINK_CODE"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);