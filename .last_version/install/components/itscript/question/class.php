<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\PageNavigation;
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

            // Get ORM entity
            $questions = QuestionTable::getList([
                'select' => [
                    '*', 
                    'U_NAME' => 'USER.NAME', 
                    'U_SECOND_NAME' => 'USER.SECOND_NAME', 
                    'U_LAST_NAME' => 'USER.LAST_NAME', 
                    'U_LOGIN' => 'USER.LOGIN'],
                'filter' => ['ACTIVE' => 'Y'],
                'order' => ['ID' => 'DESC'],
                'offset' => $nav->getOffset(),
                'limit' => $nav->getLimit(),
                'count_total' => true
            ]);

            // Set full count elements entity
            $nav->setRecordCount($questions->getCount());

            // Fetch all items per page
            $rows  = $questions->fetchAll();

            // Create FULL_NAME
            foreach ($rows as $k => $val) {
                $glueName = trim(implode(' ', [
                    $val['U_LAST_NAME'], 
                    $val['U_NAME'], 
                    $val['U_SECOND_NAME']
                ]));
                $rows[$k]['FULL_NAME'] = $glueName ?? $val['U_LOGIN'];
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
