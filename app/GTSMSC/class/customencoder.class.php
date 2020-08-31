<?php
namespace App\GTSMSC;
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
			'�' => "e", '�' => "e", '�' => "u", '�' => "i", '�' => "o", '�' => "c", '�' => "E", '�' => "a"
		);
		$finds = array('�', '�', '�', '�', '�', '�','�', '�', '�');
		$replaces = array("e","e","u","i","o","c","c","E","a");
		$converted = str_replace($finds, $replaces, $string);
		return $converted;
	}
}
