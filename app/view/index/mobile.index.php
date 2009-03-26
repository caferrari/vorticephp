<?
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
<h2>Página Inicial (Mobile)</h2>
<ul id="menu-principal">
	<li><a class="lnk1" href="<?= new Link("exemplo") ?>" title="{{Exemplo}}">{{Exemplo}}</a></li>
</ul>

<p><br /><?= $_SERVER['HTTP_USER_AGENT'] ?>: <?php echo is_mobile() ? "Mobile" : "Desktop" ?>, <?php echo is_bot() ? "BOT" : "Visitor" ?></p>
