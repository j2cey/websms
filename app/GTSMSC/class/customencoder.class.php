<?php
/**
 * Custom Encoder
 * @author judparfait@gmail.com
 */
class CustomEncoder
{
	
	/**
	 * Encode Accented characters
	 *
	 * @param string $string
	 * @return string
	 */
	public static function encode_accented_chars($string)
	{
		$dict = array(
			'è' => "e", 'é' => "e", 'ù' => "u", 'ì' => "i", 'ò' => "o", 'Ç' => "c", 'É' => "E", 'à' => "a"
		);
		$finds = array('è', 'é', 'ù', 'ì', 'ò', 'Ç','ç', 'É', 'à');
		$replaces = array("e","e","u","i","o","c","c","E","a");
		$converted = str_replace($finds, $replaces, $string);
		return $converted;
	}
}