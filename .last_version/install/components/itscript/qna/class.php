<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\FileTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Engine\CurrentUser;
use Itscript\Qna\QnaTable;
use Itscript\Qna\Util;

Loader::includeModule('itscript.qna');

class Qna extends CBitrixComponent
{
	public function onPrepareComponentParams($arParams) {

        /*echo '<pre>';
        print_r($arParams);
        echo '</pre>';*/

		$result = [
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => isset($arParams["CACHE_TIME"])? $arParams["CACHE_TIME"]: 36000000,
        ];

        $result = $result+$arParams;

		return $result;
	}

	public function executeComponent() {

		if ($this->startResultCache(false, array(($this->arParams["CACHE_GROUPS"]==="N"? false: CurrentUser::get()->getUserGroups())))) {
	        
            // add assets
            Asset::getInstance()->addCss($this->GetPath() . '/templates/' . $this->getTemplateName() . '/style.css');
            Asset::getInstance()->addJs($this->GetPath().'/templates/'. $this->getTemplateName() . '/js/script.js');

            // Create navigation
            $nav = new PageNavigation("nav");
            $nav->allowAllRecords(false)
                ->setPageSize($this->arParams['LIMIT'])
                ->initFromUri();

            $filter = ['ENTITY_ID' => intval($this->arParams['ENTITY_ID'])];
            if ($this->arParams['USE_PREMODERATION'] == 'Y') {
                $filter['ACTIVE'] = 'Y';
            }

            // Get ORM entity
            $questions = QnaTable::getList([
                'select' => [
                    '*', 
                    'U_LOGIN' => 'USER.LOGIN',
                    'U_NAME' => 'USER.NAME',  
                    'U_LAST_NAME' => 'USER.LAST_NAME', 
                    'U_PHOTO' => 'USER.PERSONAL_PHOTO',
                    'U_SECOND_NAME' => 'USER.SECOND_NAME',

                    'A_LOGIN' => 'ADMIN.LOGIN',
                    'A_NAME' => 'ADMIN.NAME',  
                    'A_LAST_NAME' => 'ADMIN.LAST_NAME', 
                    'A_PHOTO' => 'ADMIN.PERSONAL_PHOTO',
                    'A_SECOND_NAME' => 'ADMIN.SECOND_NAME',
                ],
                'filter' => $filter,
                'order' => ['ID' => 'DESC'],
                'offset' => $nav->getOffset(),
                'limit' => $nav->getLimit(),
                'count_total' => true
            ]);

            // Set full count elements entity
            $nav->setRecordCount($questions->getCount());

            // Fetch all items per page
            $rows  = $questions->fetchAll();

            //Util::debug($rows);

            // Create FULL_NAME
            foreach ($rows as $k => $val) {
                $rows[$k]['U_FULL_NAME'] = $this->getFullName($val, 'U');

                // User photo
                if ($val['U_PHOTO']) {
                    $file = \CFile::GetFileArray($val['U_PHOTO']);
                    $rows[$k]['U_PHOTO'] = $file['SRC'];
                }

                $rows[$k]['A_FULL_NAME'] = $this->getFullName($val, 'A');

                // Admin photo
                if ($val['A_PHOTO']) {
                    $file = \CFile::GetFileArray($val['A_PHOTO']);
                    $rows[$k]['A_PHOTO'] = $file['SRC'];
                }
            }

            $this->arResult["ITEMS"] = $rows;
            $this->arResult['NAV'] = $nav;

            // Save data cache
            $this->SetResultCacheKeys(['ITEMS', 'NAV']);

            // Include template
            $this->includeComponentTemplate();

	    } else {
            $this->abortResultCache();
        }
	}

    // Create full name user
    public function getFullName(array $arr, string $sfx): string {

        $glueName = trim(implode(' ', [
            $arr["{$sfx}_LAST_NAME"], 
            $arr["{$sfx}_NAME"], 
            $arr["{$sfx}_SECOND_NAME"]
        ]));

        return trim($glueName) ?: $arr["{$sfx}_LOGIN"];
    }

}