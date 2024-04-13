<?php
namespace Itscript\Answer;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Entity\Validator\Unique;

class AnswerTable extends Entity\DataManager
{
	public static function getMap()
	{
		return [
			new Entity\IntegerField('ID', [
				'title' => 'ID',
				'primary' => true,
				'autocomplete' => true
            ]),
			new Entity\IntegerField('USER_ID', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_USER_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),
            new Entity\BooleanField('ACTIVE', [
				'title' => 'ACTIVE',
                'values' => array('N', 'Y')
            ]),
			new Entity\IntegerField('QUESTION_ID', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_QUESTION_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),
			new Entity\StringField('ANSWER', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_ANSWER'),
                'required' => true,
				'size' => 700,
				'validation' => function () {
					return [
						new Length(null, 8000),
					];
				},
            ]),
			new Entity\DateField('PUBLISH_DATE', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_PUBLISH_DATE'),
				'default_value' => new Type\Date
			])
        ];
	}
}