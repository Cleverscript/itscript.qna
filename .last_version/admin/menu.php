<?php
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\ModuleManager;

IncludeModuleLangFile(__FILE__);

$module_id = "itscript.qna";

global $APPLICATION, $adminMenu;

if ($APPLICATION->GetGroupRight($module_id)!="D") {

    $arMenu = array(
        'parent_menu' => 'global_itscript',
        'text' => Loc::getMessage('ITSCRIPT_QNA_MENU_ROOT_NAME'),
        'icon' => "constructor-menu-icon",
        'page_icon' => 'constructor-menu-icon',
        'items_id' => 'intec_constructor',
        'items' => array(

            array(
                'text' => Loc::getMessage('ITSCRIPT_QNA_MENU'),
                'icon' => 'constructor-menu-icon-blocks-templates',
                'page_icon' => 'constructor-menu-icon-blocks-templates',
                'url' => '/bitrix/admin/itscript_qna_list.php',
                'more_url' => array(),
                'items_id' => 'main'
            ),
            
        )
    );
    
    return $arMenu;
}

return false;