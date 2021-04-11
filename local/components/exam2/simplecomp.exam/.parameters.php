<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "PARAMETERS" => array(
        "NEWS_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "EXAM2_AUTHOR_PROPERTY" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_AUTHOR_PROPERTY"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PROPERTY_AUTHOR_TYPE_UF" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_AUTHOR_TYPE_UF"),
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