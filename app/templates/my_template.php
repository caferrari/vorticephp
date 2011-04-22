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
	<title>{{Sistema Acadêmico EaD}}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Manoel Campos da Silva Filho" />
	<meta name="language" content="<?php echo request_lang?>" />
	<!--csstags-->
	<script>
		var rootvirtual = '<?php echo virtualroot ?>';
	</script>
	<!--As bibliotecas foram adicionadas diretamente na pasta js em webroot
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
  -->

	<!--jstags-->
</head>
<body onload="focus();">
<div id="conteiner">
	<div id="topo">
		<!--<h1><a class="hidetxt" href="<? echo virtualroot . request_lang ?>" title="{{Página inicial}}">{{Página inicial}}</a></h1>-->
	</div>
	<div id="conteudo">
    <?if(Vortice::getView() != "usuario/login"): ?>
    <ul id="menu-principal" class="submenu">
	    <li><a class="lnk1" href="<?php echo new Link("") ?>" title="{{Página Inicial}}">{{Página Inicial}}</a></li>
	    <li><a class="lnk1" href="<?php echo new Link("curso") ?>" title="{{Curso}}">{{Curso}}</a></li>
      <li><a class="lnk1" href="<?php echo new Link("turma") ?>" title="{{Turma}}">{{Turma}}</a></li>
    </ul>

    <div width="100%" align="right">
      <ul id="menu-principal" class="submenu">
        <li align="right"><a class="lnk1" href="<?=new Link('usuario:logout')?>">Sair do Sistema</a></li>
      </ul>
    </div>
    <? endif; ?>

		<!--menu-->
		<!--message-->
		<!--content-->
	</div>
	<div id="rodape"><a href="http://manoelcampos.com/contato" title="Desenvolvedor">Desenvolvido por Manoel Campos</a></div>
</div>
</body>
</html>
