<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
	"GROUPS" => [],
	"PARAMETERS" => [
		"AJAX_MODE" => [],

		"LIMIT" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_QUESTION_CONT"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		],

		"ENTITY_ID" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_QUESTION_ENTITY_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		],

		"CACHE_TIME"  =>  ["DEFAULT"=>36000000],
		"CACHE_GROUPS" => [
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BP_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		],
	],
];

/*
CIBlockParameters::AddPagerSettings(
	$arComponentParameters,
	GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), //$pager_title
	true, //$bDescNumbering
	true, //$bShowAllParam
	true, //$bBaseLink
	($arCurrentValues["PAGER_BASE_LINK_ENABLE"] ?? '') ==="Y" //$bBaseLinkEnabled
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
*/