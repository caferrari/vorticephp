<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
 /**
 * Application template sample
 * @package SampleApp
 * @subpackage Template
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= request_lang?>" lang="<?= request_lang?>">
<head>
	<title><!--titulo--></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-cache, no-store" />
	<meta http-equiv="Pragma" content="no-cache, no-store" />
	<meta http-equiv="expires" content="Mon, 06 Jan 1990 00:00:01 GMT" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<meta name="robots" content="" />
	<meta name="rating" content="General" />
	<meta name="author" content="Carlos André Ferrari" />
	<meta name="language" content="<?= request_lang?>" />
	<meta name="doc-rights" content="Private" />
	<link rel="shortcut icon" href="<?= rootvirtual . "/img/favicon.gif" ?>">
	<!--csstags-->
	<script>
		var rootvirtual = '<?= rootvirtual ?>';
	</script>
	<!--jstags-->
</head>
<body>
<div id="conteiner">
	<ul id="lang">
		<li><a href="<?= rootvirtual . uri ?>" lang="<?=default_lang?>"><?=default_lang?></a></li>
		<li><a href="<?= rootvirtual ?>en/<?= uri ?>" lang="en">en</a></li>
	</ul>
	<div id="topo">
		<h1><a class="hidetxt" href="<? echo rootvirtual . request_lang ?>" title="{{Página inicial}}">{{Exemplo de Sistema}}</a></h1>
	</div>
	<div id="conteudo">
		<!--menu-->
		<!--mensagem-->
		<!--conteudo-->
	</div>
	<!--plugin:inc:rodape:index-->
</div>
</body>
</html>
