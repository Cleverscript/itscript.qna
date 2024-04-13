<?php
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$module_id = "itscript.question";

$defaultOptions = Option::getDefaults($module_id);

define("ITSCRIPT_QUESTION_MODULE_ID", $module_id);