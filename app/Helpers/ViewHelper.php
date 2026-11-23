<?php

namespace App\Helpers;

class ViewHelper
{
	/**
	 *#	Get Device
	 * 	Verifica qual é o dispositivo que o usuário está acessando o site:
	 *
	 * 	@return string 	Nome do arquivo de thumbnail com o sufixo da razão de aspecto desejada.
	 * 	@access public
	 */
	public static function isMobile()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];

		// Padrões comuns de User Agents móveis:
		$mobileAgents = [
			'Mobile', 'Android', 'Silk/', 'Kindle', 'BlackBerry', 'Opera Mini', 'Opera Mobi', 
			'iPhone', 'iPod', 'iPad', 'IEMobile', 'Windows Phone', 'webOS'
		];

		// Verifica se o User Agent contém algum dos padrões de dispositivos móveis
		foreach ($mobileAgents as $agent) {
			if (strpos($userAgent, $agent) !== false) {
				return true;
			}
		}

		return false;
	}

	public static function fixFullUrlVite($url)
	{
		if( strpos($url, 'http') !== false )
			return $url;

		return url('/') . $url;
	}
}
