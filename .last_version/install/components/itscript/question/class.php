<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
class Question extends CBitrixComponent
{
	public function onPrepareComponentParams($arParams) {
		$result = [
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => isset($arParams["CACHE_TIME"])? $arParams["CACHE_TIME"]: 36000000,
			"X" => intval($arParams["X"]),
        ];
		return $result;
	}

	public function executeComponent() {

        global $APPLICATION, $USER;

        if ($this->arParams["DISPLAY_PAGER"]) {
            $arNavParams = array(
                "nPageSize" => $this->arParams["NEWS_COUNT"],
                "bDescPageNumbering" => $this->arParams["PAGER_DESC_NUMBERING"],
                "bShowAll" => $this->arParams["PAGER_SHOW_ALL"],
            );
            $arNavigation = CDBResult::GetNavParams($arNavParams);
            if ((int)$arNavigation["PAGEN"] === 0 && $this->arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0) {
                $arParams["CACHE_TIME"] = $this->arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
            }
        } else {
            $arNavParams = array(
                "nTopCount" => $this->arParams["NEWS_COUNT"],
                "bDescPageNumbering" => $this->arParams["PAGER_DESC_NUMBERING"],
            );
            $arNavigation = false;
        }
        
        $pagerParameters = [];
        if (!empty($arParams["PAGER_PARAMS_NAME"]) && preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $this->arParams["PAGER_PARAMS_NAME"])) {
            $pagerParameters = $GLOBALS[$this->arParams["PAGER_PARAMS_NAME"]] ?? [];
            if (!is_array($pagerParameters)) {
                $pagerParameters = array();
            }
        }

		if ($this->startResultCache(false, array(($this->arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation, $pagerParameters))) {
	        
            // TODO rewrite to D7
            $APPLICATION->SetAdditionalCSS($this->GetPath() . '/templates/' . $this->arParams['COMPONENT_TEMPLATE'] . '/style.css');
            $APPLICATION->AddHeadScript($this->GetPath().'/templates/'. $this->arParams['COMPONENT_TEMPLATE'] . '/script.js');
		    

            $this->arResult["ITEMS"] = [];
            $this->arResult["VAR"] = 1000;

            $this->SetResultCacheKeys(array());
            $this->includeComponentTemplate();

	    } else {
            $this->abortResultCache();
        }

	}

}
