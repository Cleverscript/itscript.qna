
<div class="question-form-over">
    <button id="question-add-btn-js" class="btn btn-primary question-add-btn">
        <?=GetMessage("T_QUESTION_ADD");?>
    </button>
    <div id="question-form-over-js" class="question-form">
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
            <div class="question-content-wrap">
                <div class="question-author-photo" style="background: url('<?=$item["U_PHOTO"];?>');"></div>
                <div class="content-over">

                    <strong><?=$item["U_FULL_NAME"];?></strong>
                    <?php if($arParams['DISPLAY_DATE']=='Y'): ?>
                    <span class="publish-date"><?=$item["PUBLISH_DATE"];?></span>
                    <?php endif; ?>
                    <div class="text"><?=$item["QUESTION"];?></div>
                
                </div>
            </div>
            <?php if (!empty($item["ANSWER"])): ?>
            <div class="question-content-wrap answer-content-wrap">
                <div class="question-author-photo" style="background: url('<?=$item["A_PHOTO"];?>');"></div>
                <div class="content-over">

                    <strong><?=($item["A_FULL_NAME"])??$item["A_LOGIN"];?></strong>
                    <?php if($arParams['DISPLAY_DATE']=='Y'): ?>
                    <span class="publish-date"><?=$item["PUBLISH_DATE_ANSWER"];?></span>
                    <?php endif; ?>

                    <div class="text"><?=$item["ANSWER"];?></div>
                </div>
            </div>
            <?php endif; ?>
            <p class="clr"></p>
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

