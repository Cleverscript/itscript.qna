<?php
namespace Itscript\Qna;

use Bitrix\Main\UserTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Entity\Validator\RegExp;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Query\Join;

class QnaTable extends DataManager
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
				'title' => Loc::getMessage('QNA_TABLE_TITLE_USER_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),

			(new Reference(
					'USER',
					UserTable::class,
					Join::on('this.USER_ID', 'ref.ID')
			))->configureJoinType('inner'),


			new IntegerField('ADMIN_ID', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_ADMIN_ID'),
				/*'required' => true,*/
				'format' => '/^[0-9]{1,}$/',
            ]),

			(new Reference(
					'ADMIN',
					UserTable::class,
					Join::on('this.ADMIN_ID', 'ref.ID')
			))->configureJoinType('inner'),

			new IntegerField('ENTITY_ID', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_ENTITY_ID'),
				'required' => true,
				'format' => '/^[0-9]{1,}$/',
            ]),

            new BooleanField('ACTIVE', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_ACTIVE'),
                'values' => array('N', 'Y')
            ]),

			new StringField('URL', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_URL'),
                'required' => true,
				'size' => 1000,
				'validation' => function () {
					return [
						new Length(null, 1000),
						new RegExp('/[h][t][t][p][s]?[:\/\/]/')
					];
				},
            ]),

			new StringField('QUESTION', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_QUESTION'),
                'required' => true,
				'size' => 8000,
				'validation' => function () {
					return [
						new Length(null, 8000),
					];
				},
            ]),

			new StringField('ANSWER', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_ANSWER'),
				'size' => 8000,
				'validation' => function () {
					return [
						new Length(null, 8000),
					];
				},
            ]),

			new DatetimeField('PUBLISH_DATE', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_PUBLISH_DATE'),
				'default_value' => new DateTime
			]),

			new DatetimeField('PUBLISH_DATE_ANSWER', [
				'title' => Loc::getMessage('QNA_TABLE_TITLE_PUBLISH_DATE_ANSWER'),
				'default_value' => new DateTime
			]),

        ];
	}
}