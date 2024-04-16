<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("T_QUESTION"),
	"DESCRIPTION" => GetMessage("T_QUESTION_DESC"),
	"ICON" => "/images/news_list.gif",
	"SORT" => 1,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "Itscript",
		"CHILD" => array(
			"ID" => "question",
			"NAME" => GetMessage("T_QUESTION"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "question_cmpx",
			),
		),
	),
);