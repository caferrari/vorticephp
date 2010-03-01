<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Crypt class, to encrypt strings using XOR operator
 * More on: http://ferrari.eti.br/operador-xor-no-php/ (pt-br)
 *
 * @version	1
 * @package	Utils
 * @author	Carlos André Ferrari <carlos@ferrari.eti.br>
 * @author	Luan Almeida <luanlmd@gmail.com>
 */
class Crypt {

	/**
	* Default encrypt key
	*
	* @staticvar	string
	*/
	static $chave = apphash;
	
	/**
	* Encrypt or decrypt data using XOR operator
	*
	* @param	string	$a		String to be encoded
	* @param	string	$b		Key
	* @return	string
	* @access	private
	* @static
	*/
	private static function StringXor($a,$b) {
		if ($a=='') return '';
		$retorno = ''; $i = strlen($a)-1; $j = strlen($b);
		do{
			$retorno .= ($a{$i} ^ $b{$i % $j});
		}while ($i--);
		return strrev($retorno);
	}

	/**
	* Encrypt a sring
	*
	* @param	string	$string		String to be encoded
	* @param	string	$chave		Key
	* @return	string
	* @static
	*/
	static function Encrypt($string, $chave=false) {
		if (!is_string($string)) throw(new StringRequiredException());
		if (!$chave) $chave = self::$chave;
		return base64_encode(self::StringXor($string, $chave));
	}
	
	/**
	* Decrypt a sring
	*
	* @param	string	$string		String to be decoded
	* @param	string	$chave		Key
	* @return	string
	* @static
	*/
	static function Decrypt($string, $chave=false) {
		if ($string != '' && !is_string($string)) throw(new StringRequiredException());
		if (!$chave) $chave = self::$chave;
		return self::StringXor(base64_decode($string), $chave);
	}
}
