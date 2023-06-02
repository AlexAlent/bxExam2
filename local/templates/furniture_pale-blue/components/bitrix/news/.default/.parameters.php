<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"REPORT_AJAX" => Array(
		"NAME" => GetMessage("REPORT_AJAX"),
		"TYPE" => "CHECKBOX",
		"MULTIPLE" => "N",
		"DEFAULT" => "Y",
		"PARENT" => 'DETAIL_SETTINGS',
	),
);
?>
