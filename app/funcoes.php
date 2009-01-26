<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Application functions
 * @package SampleApp
 */

/*

// Descomente este bloco para habilitar o suporte à URL's Encriptadas

function getSessionKey($id){
	if (Session::get("link_key_$id")=="")
		Session::set("link_key_$id", substr(crypt(date("U") . $id), -10));
	return Session::get("link_key_$id");
}

function link_encode($l){
	$ck = getSessionKey(1);
	$cl = getSessionKey(2);
	$link = json_decode($l);
	$link->_chave = base64_encode(Crypt::Encrypt(json_encode($link), $ck));
	return rootvirtual .  Crypt::Encrypt(json_encode($link), $cl);
}

function link_decode($l){
	$ck = getSessionKey(1);
	$cl = getSessionKey(2);
	if ($l=='') return $l;
	$link = json_decode(Crypt::Decrypt($l, $cl));
	$chave = @$link->_chave;
	unset($link->_chave);
	$nova_chave = base64_encode(Crypt::Encrypt(json_encode($link), $ck));
	if ($chave != $nova_chave)
		throw (new Exception("URL violada!"));
	return $link;
}
*/

?>
