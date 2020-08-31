<?php


namespace App\Traits\SMS;

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
            'é' => "e", 'è' => "e", 'ù' => "u", 'ì' => "i", 'ò' => "o", 'ç' => "c", 'E' => "E", 'à' => "a"
        );
        $finds = array('é','è','ù','ì','ò','ç','ç', 'E', 'à');
        $replaces = array("e","e","u","i","o","c","c","E","a");
        $converted = str_replace($finds, $replaces, $string);
        return $converted;
    }
}
