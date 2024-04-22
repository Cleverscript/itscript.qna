# Q&A - модуль "Вопрос - ответ" для 1C-Bitrix

---

Модуль позволяет организовать вопросы и ответы для любых сущностей, с привязкой к ID сущности.

---

### Установка

- 1. Загрузите архив с модулем в директорию /bitrix/modules используя FTP или через админку
- 2. Распакуйте архив с модулем
- 3. Переименуйте появившуюся директорию /bitrix/modules/.last_version в /bitrix/modules/itscript.qna
- 4. Установите модуль стандартным образом (Рабочий стол => Marketplace => Установленные решения)
- 5. Встройте на страницу товара или любой другой сущности у которой есть ID компонент (передав значение в параметр "ENTITY_ID" компонента)

```php
<?php $APPLICATION->IncludeComponent(
	"itscript:qna", 
	".default", 
	array(
		"COUNT" => "10",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"ENTITY_ID" => "1",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"LIMIT" => "5",
		"NAV_SEF_MODE" => "N",
		"USE_PREMODERATION" => "Y"
	),
	false
);?>
```

![Иллюстрация к проекту](https://github.com/Cleverscript/itscript.qna/raw/main/prev-1.png)
![Иллюстрация к проекту](https://github.com/Cleverscript/itscript.qna/raw/main/prev-2.png)
![Иллюстрация к проекту](https://github.com/Cleverscript/itscript.qna/raw/main/prev-3.png)