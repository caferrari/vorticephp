<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Sample of a framework view to the start page
 * @package SampleApp
 * @subpackage View
 */
?>
<h2>{{Página Inicial}}</h2>
<ul id="menu-principal">
	<li><a class="lnk1" href="<?php echo new Link("orgao") ?>" title="{{Exemplo}}">{{Exemplo}}</a></li>
</ul>
<?php 
	new Snippet('info');
?>
