<?php
namespace Itscript\Qna\Controller;

use Bitrix\Main\Error;
use Itscript\Qna\QnaTable;
use Bitrix\Main\Engine\Controller;

class Item extends Controller
{
	public function addAction(array $fields):? array
	{
        global $USER;

        $result = QnaTable::add(array(
            'USER_ID' => $USER->GetID(),
            'ENTITY_ID' => $fields['ENTITY_ID'],
            'ACTIVE' => $fields['ACTIVE'],
            'URL' => $fields['URL'],
            'QUESTION' => $fields['QUESTION'],
        ));
        if (!$result->isSuccess())
        {
            $this->addError(new Error($result->getErrorMessages()));
            return null;
        }
        
        $id = $result->getId();

		return ['ID' => $id];
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