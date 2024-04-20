<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_DISPLAY_PUBLUSH_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),

	"NAV_SEF_MODE" => Array(
		"NAME" => GetMessage("T_NAV_SEF_MODE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	)
);
