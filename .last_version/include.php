<?php
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$module_id = "itscript.qna";

$defaultOptions = Option::getDefaults($module_id);

define("ITSCRIPT_QUESTION_MODULE_ID", $module_id);
define("ITSCRIPT_QNA_CONFIG_DEBUG", Option::get('ITSCRIPT_QNA_CONFIG_DEBUG', $defaultOptions['ITSCRIPT_QNA_CONFIG_DEBUG']));