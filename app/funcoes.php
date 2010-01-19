<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Application functions
 * @package SampleApp
 */


// Descomente este bloco para habilitar o suporte à URL's Encriptadas

/*
function getSessionKey($id='key'){
	if (Session::get("link_key_$id")=="")
		Session::set("link_key_$id", substr(crypt(date("U") . $id), -10));
	return Session::get("link_key_$id");
}

function link_encode($l){
	$lnk = unserialize($l);
	$lnk["chave"] = md5($l);
	$lnk = json_encode($lnk);
	$lnk = Crypt::Encrypt($lnk, getSessionKey());
	return $lnk . "/";
}

function link_decode($l){
	if (!$l) return;
	$l = Crypt::Decrypt($l, getSessionKey());
	$l = (array) json_decode($l);

	$chave = (isset($l['chave'])) ? $l['chave'] : '';
	unset ($l["chave"]);
	if (isset($l["pars"])) 
		$l["pars"] = (array)$l["pars"]; 
	$l = serialize($l);
	if ($chave == md5($l)) return $l;
	throw new Exception("ops!");
}
*/
