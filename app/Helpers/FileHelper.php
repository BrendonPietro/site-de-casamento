<?php

namespace App\Helpers;

use Illuminate\Database\Console\Migrations\StatusCommand;

class FileHelper
{
	/**
	 * Recupera o arquivo de thumbnail de uma imagem recortada baseada em uma razão de aspecto:
	 * @param string 	$fileName 				Nome do arquivo original
	 * @param string 	$aspectRatio 			Razão de aspecto desejada (ex: 16:9)
	 *
	 * @return string 	Nome do arquivo de thumbnail com o sufixo da razão de aspecto desejada.
	 * @access public
	 */
	public static function getAspectRatioThumb($fileName, $aspectRatio)
	{
		$aspectRatio = str_replace(':', '-', $aspectRatio);
		$aspectRatio = str_replace('/', '-', $aspectRatio);

		$ext = FileHelper::getFileExtension($fileName);
		$fileNameWithoutExtension = str_replace('.' . $ext, '', $fileName);
		return $fileNameWithoutExtension . '_' . $aspectRatio . '.' . $ext;
	}

	/**
	 * Recupera a extensão de um arquivo.
     * @param string 	$fileName                 Nome do arquivo 
     *
     * @return string 	Extensão do arquivo.
     * @access public
	 */
	public static function getFileExtension($fileName)
	{
        return mb_strtolower(substr($fileName, strrpos($fileName, ".") + 1));
	}

	/**
	 * Retorna os parametros que devem ser concatenados com as urls de arquivos para gerar o cache de acordo com a ultima data de atualização
	 *
	 * @param string $dateTimeUpdate Ultima data de atualização do arquivo no formato 'Y-m-d H:i:s'
	 * @return string Parametro para ser concatenado na url contatendo o timestamp da ultima atualização do arquivo
	 */
	public static function getCacheUrlParam($dateTimeUpdate)
	{
		return !empty($dateTimeUpdate) ? '?v=' . strtotime($dateTimeUpdate) : '';
	}
}
