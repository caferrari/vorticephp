<?php
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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo request_lang?>" lang="<?php echo request_lang?>">
<head>
	<title>{{Exemplo de Sistema}} (Mobile)</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Carlos André Ferrari" />
	<meta name="language" content="<?= request_lang?>" />
	<!--csstags-->
</head>
<body>
<div id="conteiner">
	<ul id="lang">
		<li><a href="<?php echo rootvirtual ?>" lang="pt-br">pt-br</a></li>
		<li><a href="<?php echo rootvirtual ?>en/<?php uri ?>" lang="en">en</a></li>
		<li><a href="<?php echo rootvirtual ?>jp/<?php uri ?>" lang="jp">日本語</a></li>
	</ul>
	<div id="topo">
		<h1><a class="hidetxt" href="<? echo rootvirtual . request_lang ?>" title="{{Página inicial}}">{{Exemplo de Sistema}} (Mobile)</a></h1>
	</div>
	<div id="conteudo">
		<!--menu-->
		<!--mensagem-->
		<!--conteudo-->
	</div>
	<div id="rodape"><a href="http://github.com/caferrari/vorticephp" title="Project at GitHub!">Check the project at Github</a></div>
</div>
</body>
</html>
