<?php
namespace Itscript\Book;

use Bitrix\Main;
use Bitrix\Main\Config\Option;

IncludeModuleLangFile(__FILE__);

class General
{
    const MODULE_ID = "itscript.config";

    /**
     * Function print var
     * @param $value
     */
    public static function debug($value)
    {
        echo "<br/><pre style='padding:10px; border:1px solid #DDD; background-color:#EEE; text-color:#000; font-family:Verdana; font-size:13px;'>";

        switch (gettype($value))
        {
            case 'integer':
            case 'double':
            case 'string':
            case 'array':
                print_r($value);
                break;
            default:
                var_dump($value);
                break;
        }

        echo "</pre><br/>";
    }


	public static function writeSysLog($auditTypeId, $itemId, $description, $severity = 'DEBUG', $moduleId = self::MODULE_ID)
	{
		//if(empty($auditTypeId) || !intval($itemId) || ITSERW_COWMS_USE_SYSLOG != 'Y')
			//return false;

		\CEventLog::Add(array(
			"SEVERITY" => $severity,
			"AUDIT_TYPE_ID" => $auditTypeId,
			"MODULE_ID" => $moduleId,
			"ITEM_ID" => $itemId,
			"DESCRIPTION" => $description,
		));
	}

}