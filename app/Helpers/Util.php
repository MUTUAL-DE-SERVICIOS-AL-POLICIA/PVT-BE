<?php

namespace Muserpol\Helpers;
use DateTime;

class Util
{
    //cambia el formato de la fecha a cadena
    //input $string=YYYY/mm/dd
    public static function getStringDate($string = "1800/01/01"){        
        setlocale(LC_TIME, 'es_ES.utf8');        
        $date = DateTime::createFromFormat("Y-m-d", $string);
        if($date)
            return strftime("%d de %B de %Y",$date->getTimestamp());
        else 
            return "sin fecha";
    }

    public static function formatMoney($value)
    {
        if ($value) {
            $value = number_format($value, 2, ',', '.');
            return $value;
        }
        return null;
    }


    public static function ucw($string)
	{
		if ($string) {
			return ucwords(mb_strtolower($string,'UTF-8'));
		}
	}
}