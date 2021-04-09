<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "CLASSF_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASSF_IBLOCK_ID"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "LINK_TEMPLATE" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_LINK_TEMPLATE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PROPERTY_CODE" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_PROPERTY_CODE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
	),
);