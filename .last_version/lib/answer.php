<?php
namespace Itscript\Answer;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Data\DataManager\UserTable;

class AnswerTable extends DataManager
{
	public static function getMap()
	{
		return [
			new IntegerField('ID', [
				'title' => 'ID',
				'primary' => true,
				'autocomplete' => true
            ]),

			new IntegerField('USER_ID', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_USER_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),

			(new Reference(
				'USER',
				DataManager\UserTable::class,
				Join::on('this.USER_ID', 'ref.ID')
			))->configureJoinType('inner'),

            new BooleanField('ACTIVE', [
				'title' => 'ACTIVE',
                'values' => array('N', 'Y')
            ]),

			new IntegerField('QUESTION_ID', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_QUESTION_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),

			new StringField('ANSWER', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_ANSWER'),
                'required' => true,
				'size' => 700,
				'validation' => function () {
					return [
						new Length(null, 8000),
					];
				},
            ]),

			new DateField('PUBLISH_DATE', [
				'title' => Loc::getMessage('ANSWER_TABLE_TITLE_PUBLISH_DATE'),
				'default_value' => new Date
			])
        ];
	}
}