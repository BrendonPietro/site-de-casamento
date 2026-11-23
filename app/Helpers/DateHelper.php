<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class DateHelper
{
	/**
	 * A partir de uma data, retorna o dia da semana correspondente.
	 * @param string 	$date	 				Timestamp da data desejada
	 * @param string 	$type		 			Tipo da conversão: full / min / slug / slug-reduced / slug-min
	 * @param string 	$locale		 			Idioma da conversão
	 *
	 * @return string 	Nome do dia da semana com as configurações aplicadas
	 * @access public
	 */
	public static function getWeekDayName($date, $type = "full", $locale = "pt_BR")
	{
		// Set the Carbon to the desired location:
		Carbon::setLocale($locale);

		// Convert the value:
		switch ($type) {
			// Segunda-Feira / Monday
			case "full":			return Carbon::parse($date)->translatedFormat('l');
									break;
			
			// Seg / Mon
			case "min":				return Carbon::parse($date)->format('D');
									break;
			
			// segunda-feira / monday
			case "slug":			return Str::slug(Carbon::parse($date)->translatedFormat('l'));
									break;

			// segunda / monday
			case "slug-reduced":	return Str::slug(str_replace("-feira", "", Carbon::parse($date)->translatedFormat('l')));
									break;
			
			// seg / mon
			case "slug-min":		return Str::slug(Carbon::parse($date)->format('D'));
									break;

			// Seg / Mon
			default:				return Carbon::parse($date)->translatedFormat('D');
                    				break;
		}
	}
}
