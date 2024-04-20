
<div class="question-form-over">
    <button id="question-add-btn-js" class="btn btn-primary question-add-btn">
        <?=GetMessage("T_QUESTION_ADD");?>
    </button>
    <div class="question-form">
        <div id="question-form-js-alert" class="question-form-alert"></div>
        <form id="question-form-js" action="" method="POST">
            <div class="over-field">
                <label for="question-field"><?=GetMessage("T_QUESTION_FIELD_LABEL");?></label>
                <textarea id="question-field" name="QUESTION" cols="95"rows="5"></textarea>
            </div>
            <div class="over-field">
                <button id="question-form-btn-js" type="button" class="btn btn-primary question-send-btn">
                    <?=GetMessage("T_BUTTON_SEND");?>
                </button>
            </div>

            <input type="hidden" name="ENTITY_ID" value="<?=$arParams["ENTITY_ID"];?>"/>
        </form>
    </div>
</div>

<?php if (!empty($arResult['ITEMS'])): ?>
    <ul class="question-list">
    <?php foreach($arResult['ITEMS'] as $item): ?>
        <li>
            <p>
                <strong><?=($item["FULL_NAME"])??$item["U_LOGIN"];?></strong>
                <?php if($arParams['DISPLAY_DATE']=='Y'): ?>
                <span class="content-publish-date"><?=$item["PUBLISH_DATE"];?></span>
                <?php endif; ?>
            </p>
            <div class="content-question"><?=$item["QUESTION"];?></div>
            <div class="content-answer"><?=$item["ANSWER"];?></div>
        </li>       
    <?php endforeach; ?>
    </ul>

<hr/>    
<?php
$APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation",
	"",
	array(
		"NAV_OBJECT" => $arResult['NAV'],
		"SEF_MODE" => $arParams['NAV_SEF_MODE'],
	),
	false
);
?>
<? endif; ?>

<?php

//echo '<pre>';
//print_r($arResult);
//print_r($arParams);
//echo '</pre>';

