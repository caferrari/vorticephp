<?php

function d($var=false){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	exit();
}

function valid_uri($uri){
	return (preg_match('@/$@', $uri));
}

function decompose_uri($uri){
	$uri = preg_replace('@^/|/{2,}|/$@', '', $uri);
	$parts = explode('/', $uri);
	if ($parts[0] === '') return array();
	return $parts;
}

function redirect($url, $post_safe = false, $code = 301){
	set_header(301);
	header('location: ' . $url);
	if ($post_safe)
		exit ('codigo html de redirect');
	else exit();
}

function compose_uri($parts){
	return preg_replace('@/+@', '/', '/' . implode($parts, '/') . '/');
}

function set_header($code){
	$headers = array(
		200 => 'HTTP/1.1 200 OK',
		301	=> 'HTTP/1.1 301 Moved Permanently',
		304 => 'HTTP/1.1 304 Not Modified',
		403 => 'HTTP/1.1 403 Forbidden',
		404 => 'HTTP/1.1 404 Not Found',
		500 => 'HTTP/1.1 500 Internal Server Error',
	);

	if (isset($headers[$code])) header($headers[$code]);
}

/**
* Convert sala-de-imprensa to SalaDeImprensa
* @param	$str		string
* @return	string
*/
function camelize($str='') {
    return str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $str)));
}

/**
* Convert SalaDeImprensa to sala_de_imprensa
* @param	$str		string
* @return	string
*/
function uncamelize($str=''){
	return preg_replace('@^_+|_+$@', '', strtolower(preg_replace("/([A-Z])/", "_$1", $str)));
}

function p($var){
	return (isset($_POST[$var])) ? $_POST[$var] : '';
}
