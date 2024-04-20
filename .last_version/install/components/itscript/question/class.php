<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\FileTable;
use Bitrix\Main\Type\DateTime;
use Itscript\Question\QuestionTable;
use Itscript\Question\Util;

Loader::includeModule('itscript.question');

class Question extends CBitrixComponent
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

        global $APPLICATION, $USER;

		if ($this->startResultCache(false, array(($this->arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))) {
	        
            // add assets
            Asset::getInstance()->addCss($this->GetPath() . '/templates/' . $this->getTemplateName() . '/style.css');
            Asset::getInstance()->addJs($this->GetPath().'/templates/'. $this->getTemplateName() . '/js/script.js');

            // Create navigation
            $nav = new PageNavigation("nav");
            $nav->allowAllRecords(false)
                ->setPageSize($this->arParams['LIMIT'])
                ->initFromUri();

            $filter = [];
            if ($this->arParams['USE_PREMODERATION'] == 'Y') {
                $filter['ACTIVE'] = 'Y';
            }

            // Get ORM entity
            $questions = QuestionTable::getList([
                'select' => [
                    '*', 
                    'U_LOGIN' => 'USER.LOGIN',
                    'U_NAME' => 'USER.NAME',  
                    'U_LAST_NAME' => 'USER.LAST_NAME', 
                    'U_PHOTO' => 'USER.PERSONAL_PHOTO',
                    'U_SECOND_NAME' => 'USER.SECOND_NAME',
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
                $glueName = trim(implode(' ', [
                    $val['U_LAST_NAME'], 
                    $val['U_NAME'], 
                    $val['U_SECOND_NAME']
                ]));
                $rows[$k]['FULL_NAME'] = $glueName ?? $val['U_LOGIN'];

                // User photo
                if ($val['U_PHOTO']) {

                    //$file = FileTable::getByPrimary($val['U_PHOTO'], ['select' => ['*']])->fetchObject();
                    //Util::debug($file);

                    $file = \CFile::GetFileArray($val['U_PHOTO']);
                    //Util::debug($file);

                    $rows[$k]['U_PHOTO'] = $file['SRC'];
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

}
