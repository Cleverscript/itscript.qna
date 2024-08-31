<?php
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Config\Option;
use Itscript\Qna\Util;

$module_id = "itscript.qna";

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/options.php');
IncludeModuleLangFile(__FILE__);

Loader::includeModule($module_id);

global $APPLICATION;

$request = HttpApplication::getInstance()->getContext()->getRequest();

$defaultOptions = Option::getDefaults($module_id);

$arMainPropsTab = [
	"DIV" => "edit1",
	"TAB" => Loc::getMessage("ITSCRIPT_QNA_MAIN_TAB_SETTINGS"),
	"TITLE" => Loc::getMessage("ITSCRIPT_QNA_MAIN_TAB_SETTINGS_TITLE"),
	"OPTIONS" => [

		["ITSCRIPT_QNA_CONFIG_DEBUG", Loc::getMessage("T_ITSCRIPT_QNA_CONFIG_DEBUG"),
			$defaultOptions["ITSCRIPT_QNA_CONFIG_DEBUG"],
			["checkbox"]
        ],

    ]
];

$aTabs = [

    //MAIN PROPS
    $arMainPropsTab,

    [
        "DIV" => "edit5",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    ],
];
?>

<?php 
//Save form
if ($request->isPost() && $request["save"] && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        if (!empty($aTab['OPTIONS'])) {
            __AdmSettingsSaveOptions($module_id, $aTab["OPTIONS"]);
        }
    }
}
?>

<!-- FORM TAB -->
<?php
$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>
<?php $tabControl->Begin(); ?>
<form method="post" action="<?=$APPLICATION->GetCurPage();?>?mid=<?=htmlspecialcharsbx($request["mid"]);?>&amp;lang=<?=LANGUAGE_ID?>" name="<?=$module_id;?>">
    <?php $tabControl->BeginNextTab(); ?>

        <?php
        foreach ($aTabs as $aTab) {
            if(!empty($aTab['OPTIONS'])) {
                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
                $tabControl->BeginNextTab();
            }
        }
        ?>

        <?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php"); ?>

    <?php $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false)); ?>

    <?=bitrix_sessid_post();?>
</form>
<?php $tabControl->End(); ?>
<!-- X FORM TAB -->