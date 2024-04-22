<?php

namespace Itscript\Qna;

use Bitrix\Main\Localization\Loc;

class Menu
{
    /**
     * Event. Occurs when the main menu is built.
     * @param array $arGlobalMenu An array of the global menu.
     * @param array $arModuleMenu Module menu array.
     */
    public static function adminOnBuildGlobalMenu(&$arGlobalMenu, &$arModuleMenu) //&$arGlobalMenu, &$arModuleMenu
    {
		//add css icon menu
        $arGlobalMenu['global_itscript'] = [
            'menu_id' => 'global_itscript',
            'text' => 'Itscript',
            'title' => 'Itscript',
            'sort' => 100,
            'items_id' => 'global_itscript',
            'items' => [
            ]
        ];  
    }
}
