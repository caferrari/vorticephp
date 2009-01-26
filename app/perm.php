<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Application permissions config file generator
 * @package SampleApp
 * @subpackage SecuritySample
 */

class Crypt {

	static $chave = "afha#%&@#%&@!%ss";
	
	static function StringXor($a,$b) {
		if ($a=='') return '';
		$retorno = ""; $i = strlen($a)-1; $j = strlen($b);
		do{
			$retorno .= ($a{$i} ^ $b{$i % $j});
		}while ($i--);
		return strrev($retorno);
	}

	static function Encrypt($string, $chave='') {
		if ($chave=='') $chave = self::$chave;
		return base64_encode(self::StringXor($string, $chave));
	}
	
	static function Decrypt($string, $chave='') {
		if ($chave=='') $chave = self::$chave;
		return self::StringXor(base64_decode($string), $chave);
	}
}


/*
"" = qualquer um acessa
outros = logado + permissao
*/

$perm = array (
	"index"		=> "",
	"admin"		=> "",
	"duvida" 	=> "",
	"faq" 		=> "",
	"exemplo" 	=> "",
	"orgaos" 	=> "a",
	"usuarios" 	=> "a",
	"topicos" 	=> array
		(
		"index" => "a",
		"encaminhar" => "a",
		"encaminhados" => "a",
		"novos" => "o",
		"aguardando" => "o",
		"respondidos" => "o",
		"responder" => "o",
		"excluir" => "a",
		"detalhes" => "ao",
		"responder" => "o"
		)
);

file_put_contents("perm.conf", Crypt::Encrypt(base64_encode(json_encode($perm)), md5('Fale Conosco')));
?>
