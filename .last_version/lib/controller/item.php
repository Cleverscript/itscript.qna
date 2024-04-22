<?php
namespace Itscript\Qna\Controller;

use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Itscript\Qna\QnaTable;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Itscript\Qna\Util;

Loader::includeModule('itscript.qna');

class Item extends Controller
{
    /*public function configureActions(): array
    {
        return [
            //Название вашего Action
            'add' => [
                //Отключение фильтра
                '-prefilters' => [
                    ActionFilter\Authentication::class,
                ],
                //Включение фильтра                
                'prefilters' => [
                    ActionFilter\Csrf::class,
                ],
            ],
        ];
    }*/

	public function addAction(array $fields):? array
	{
        global $USER;

        $question = QnaTable::createObject();
        $question->set('USER_ID', $USER->GetID());
        $question->set('ENTITY_ID', $fields['ENTITY_ID']);
        $question->setActive($fields['ACTIVE']);
        $question->setUrl($fields['URL']);
        $question->setQuestion(Util::clearQuestionText($fields['QUESTION']));

        $result = $question->save();

        if (!$result->isSuccess())
        {
            $this->addError(new Error($result->getErrorMessages()));
            return null;
        }
        
        $id = $result->getId();

		return ['ID' => $id, 'ALERT' => Loc::getMessage('QNA_ADD_SUCCESS_ALERT', ['#ID#' => $id])];
	}

	public function viewAction(int $id):? array
	{

        $book = QnaTable::getByPrimary($id)->fetchObject();

        echo '<pre>';
        var_dump($book);
        echo '</pre>';

		if (!$book)
		{
			$this->addError(new Error('Could not find item.', 400));
					
			return null;
		} 

		return $book->toArray();
	}
}