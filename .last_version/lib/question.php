<?php
namespace Itscript\Question;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Entity\Validator\Unique;

class QuestionTable extends Entity\DataManager
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
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_USER_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),
			new Entity\IntegerField('ENTITY_ID', [
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_ENTITY_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),
            new Entity\BooleanField('ACTIVE', [
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_ACTIVE'),
                'values' => array('N', 'Y')
            ]),
			new Entity\StringField('URL', [
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_URL'),
                'required' => true,
				'size' => 1000,
				'validation' => function () {
					return [
						new Length(null, 1000),
					];
				},
            ]),
			new Entity\StringField('QUESTION', [
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_QUESTION'),
                'required' => true,
				'size' => 700,
				'validation' => function () {
					return [
						new Length(null, 8000),
					];
				},
            ]),
			new Entity\DateField('PUBLISH_DATE', [
				'title' => Loc::getMessage('QUESTION_TABLE_TITLE_PUBLISH_DATE'),
				'default_value' => new Type\Date
			])
        ];
	}
}