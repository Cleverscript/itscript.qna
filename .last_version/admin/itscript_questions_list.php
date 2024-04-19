<?php
/** @global CMain $APPLICATION */

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc; 
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Itscript\Question\QuestionTable;
use Bitrix\Main\UI\PageNavigation;

$module_id = "itscript.question";

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once(dirname(__FILE__)."/../include.php");
require_once(dirname(__FILE__)."/../prolog.php");

IncludeModuleLangFile(__FILE__);

// Check access
$FORM_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($FORM_RIGHT<="D") $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

if(!Loader::includeModule($module_id)){
	CAdminMessage::ShowMessage(Loc::getMessage("ITSCRIPT_QUESTION_INCLUDE_MODULE_ERROR", ['#MODULE_ID#' => $module_id]));
}

CJSCore::Init(array('ajax', 'json', 'ls', 'session', 'jquery', 'popup', 'pull'));

$adminListTableID = 'b_itscript_question';

$adminSort = new CAdminSorting($adminListTableID, 'ID', 'ASC');
$adminList = new CAdminUiList($adminListTableID, $adminSort);

$filterFields = array(
    array(
        "id" => "ID",
        "name" => 'ID',
        "filterable" => "=",
        "default" => true
    ),
    array(
        "id" => "",
        "name" => Loc::getMessage("ITSCRIPT_QUESTION_ENTITY_ID_ADMIN_FILTER"),
        "type" => "int",
        "filterable" => "="
    ),
);

$filter = array();

$adminList->AddFilter($filterFields, $filter);

if ($listID = $adminList->GroupAction()) {

    $action = $_REQUEST['action'];

    if (!empty($_REQUEST['action_button'])) {
        $action = $_REQUEST['action_button'];
    }

    $checkUseCoupons = ($action == 'delete');
    $discountList = array();

    if ($_REQUEST['action_target'] == 'selected') {
        $listID = array();
        $formIterator = QuestionTable::getList(array(
            'select' => array('ID'),
            'filter' => $filter
        ));
        while ($form = $formIterator->fetch()) {
            $listID[] = $form['ID'];
        }
        unset($form, $formIterator);
    }

    if ($adminList->IsGroupActionToAll()) {
        $arID = array();
        $formIterator = QuestionTable::getList(array(
            'select' => array('ID'),
            'filter' => $filter
        ));
        while ($arRes = $formIterator->fetch()) {
            $listID[] = $arRes['ID'];
        }
        unset($arRes, $rsData);
    }

    $listID = array_filter($listID);

    if (!empty($listID)) {
        switch ($action) {
            case 'delete':
                foreach ($listID as &$recordId) {
                    $result = QuestionTable::delete($recordId);
                    if (!$result->isSuccess()) {
                        $adminList->AddGroupError(implode('<br>', $result->getErrorMessages()), $recordId);
                    }
                    unset($result);
                }
                unset($recordId);
                break;
        }
    }
    unset($discountList, $action, $listID);

    if ($adminList->hasGroupErrors()) {
        $adminSidePanelHelper->sendJsonErrorResponse($adminList->getGroupErrors());
    } else {
        $adminSidePanelHelper->sendSuccessResponse();
    }
}

$headerList = array();
$headerList['ID'] = array(
    'id' => 'ID',
    'content' => 'ID',
    'sort' => 'ID',
    'default' => true
);
$headerList['PUBLISH_DATE'] = array(
    'id' => 'PUBLISH_DATE',
    'content' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_PUBLISH_DATE'),
    'title' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_PUBLISH_DATE'),
    'sort' => 'CREATED',
    'default' => true
);
$headerList['QUESTION'] = array(
    'id' => 'QUESTION',
    'content' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_QUESTION'),
    'title' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_QUESTION'),
    'sort' => 'QUESTION',
    'default' => false
);
$headerList['ACTIVE'] = array(
    'id' => 'ACTIVE',
    'content' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_ACTIVE'),
    'title' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_ACTIVE'),
    'sort' => 'ACTIVE',
    'default' => false
);
$headerList['URL'] = array(
    'id' => 'URL',
    'content' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_URL'),
    'title' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_URL'),
    'sort' => 'URL',
    'default' => true
);
$headerList['ENTITY_ID'] = array(
    'id' => 'ENTITY_ID',
    'content' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_ENTITY_ID'),
    'title' => Loc::getMessage('ITSCRIPT_QUESTION_TITLE_ENTITY_ID'),
    'sort' => 'ENTITY_ID',
    'default' => true
);

$listHeader = array_keys($headerList);

$adminList->AddHeaders($headerList);

$selectFields = array_fill_keys($adminList->GetVisibleHeaderColumns(), true);
$selectFields['ID'] = true;
$selectFieldsMap = array_fill_keys(array_keys($headerList), false);
$selectFieldsMap = array_merge($selectFieldsMap, $selectFields);

if (!isset($by)) {
    $by = 'ID';
}
if (!isset($order)) {
    $order = 'ASC';
}

$rowList = array();
$usePageNavigation = true;
$navyParams = array();

$navyParams = \CDBResult::GetNavParams(CAdminUiResult::GetNavSize($adminListTableID));
if ($navyParams['SHOW_ALL']) {
    $usePageNavigation = false;
} else {
    $navyParams['PAGEN'] = (int)$navyParams['PAGEN'];
    $navyParams['SIZEN'] = (int)$navyParams['SIZEN'];
}

global $by, $order;

$getListParams = array(
    'select' => $selectFields,
    'filter' => $filter,
    'order' => array($by => $order)
);

if ($usePageNavigation) {
    $getListParams['limit'] = $navyParams['SIZEN'];
    $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
}
$totalPages = 0;
if ($usePageNavigation) {
    $totalCount = QuestionTable::getCount($getListParams['filter']);
    if ($totalCount > 0) {
        $totalPages = ceil($totalCount / $navyParams['SIZEN']);
        if ($navyParams['PAGEN'] > $totalPages)
            $navyParams['PAGEN'] = $totalPages;
        $getListParams['limit'] = $navyParams['SIZEN'];
        $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
    } else {
        $navyParams['PAGEN'] = 1;
        $getListParams['limit'] = $navyParams['SIZEN'];
        $getListParams['offset'] = 0;
    }
}

$getListParams['select'] = array_keys($getListParams['select']);

/*echo '<pre>';
print_r([
    $totalCount,
    $navyParams,
    $selectFieldsMap,
    $adminListTableID,
    'getListParams' => $getListParams,
    LANGUAGE_ID
]);
//print_r([$getListParams, $adminListTableID]);
echo '</pre>';*/


$formIterator = new CAdminUiResult(QuestionTable::getList($getListParams), $adminListTableID);
if ($usePageNavigation) {
    $formIterator->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
    $formIterator->NavRecordCount = $totalCount;
    $formIterator->NavPageCount = $totalPages;
    $formIterator->NavPageNomer = $navyParams['PAGEN'];
} else {
    $formIterator->NavStart();
}
$onlyDel = false;
$yesNo = [
    Loc::getMessage("ITSCRIPT_QUESTION_TITLE_NO"),
    Loc::getMessage("ITSCRIPT_QUESTION_TITLE_YES"),
];
CTimeZone::Disable();
$adminList->SetNavigationParams($formIterator, array("BASE_LINK" => $selfFolderUrl . "itscript_question_list.php"));
while($form = $formIterator->fetch()) {
    $result[]=$form;
}
$prm['SELECT'] = $getListParams['select'];
//TenderComp::reflection($result, $prm);

foreach($result as $form)
{
    $form['ID'] = (int)$form['ID'];
    $urlEdit = $selfFolderUrl . 'itscript_question_edit.php?ID=' . $form['ID'] . '&lang=' . LANGUAGE_ID;
    $urlEdit = $adminSidePanelHelper->editUrlToPublicPage($urlEdit);

    $rowList[$form['ID']] = $row = &$adminList->AddRow(
        $form['ID'],
        $form,
        $urlEdit,
        Loc::getMessage("ITSCRIPT_QUESTION_EDIT")
    );

    if ($onlyDel) {
        $row->AddViewField('ID', $form['ID']);
    } else {
        $row->AddViewField('ID', '<a href="' . $urlEdit . '">' . $form['ID'] . '</a>');
    }

    if ($selectFieldsMap['URL']) {
        $row->AddViewField('URL', '<a href="' . $form['URL'] . '">' . $form['URL'] . '</a>');
    }

    if ($selectFieldsMap['QUESTION']) {
        $row->AddViewField('QUESTION', $form['QUESTION']);
    }
    
    if ($selectFieldsMap['PUBLISH_DATE']) {
        $row->AddViewField('PUBLISH_DATE', $form['PUBLISH_DATE']->format('d.m.Y H:i:s'));
    }

    if ($selectFieldsMap['ACTIVE']) {
        $row->AddViewField('ACTIVE', $yesNo[$form['ACTIVE']]);
    }

    $actions = array();
    if (!$onlyDel) {
        $actions[] = array(
            'ICON' => 'edit',
            'TEXT' => Loc::getMessage("ITSCRIPT_QUESTION_EDIT"),
            'LINK' => $urlEdit,
            'DEFAULT' => true
        );
    }
    if (!$readOnly) {
        $actions[] = array(
            'ICON' => 'delete',
            'TEXT' => Loc::getMessage("ITSCRIPT_QUESTION_DELETE"),
            'ACTION' => "if (confirm('" . Loc::getMessage("ITSCRIPT_QUESTION_DELETE_ALERT") . "')) " . $adminList->ActionDoGroup($form['ID'], 'delete')
        );
    }
    $row->AddActions($actions);
    unset($actions, $row);
}
CTimeZone::Enable();

$adminList->AddGroupActionTable([
    'delete' => true,
    'for_all'=>true,

]);

$contextMenu = array();

if (!$readOnly) {
    $addUrl = $selfFolderUrl . "itscript_question_edit.php?lang=" . LANGUAGE_ID;
    $addUrl = $adminSidePanelHelper->editUrlToPublicPage($addUrl);
    $contextMenu[] = array(
        'ICON' => 'btn_new',
        'TEXT' => Loc::getMessage('ITSCRIPT_QUESTION_ADD'),
        'TITLE' => Loc::getMessage('ITSCRIPT_QUESTION_ADD'),
        'LINK' => $addUrl
    );
}

if (!empty($contextMenu)) {
    $adminList->setContextSettings(array("pagePath" => $selfFolderUrl . "itscript_question_list.php"));
    $adminList->AddAdminContextMenu($contextMenu);
}

$adminList->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage("ITSCRIPT_QUESTION_PAGE_TITLE"));

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

$adminList->DisplayFilter($filterFields);
$adminList->DisplayList();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
