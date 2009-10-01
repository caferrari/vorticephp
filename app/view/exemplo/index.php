<?
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Sample of a framework view to List Exemplo objects
 * @package SampleApp
 * @subpackage View
 */
?>
<h2>{{Itens}}</h2>
<span class="desc"><!--desc--></span>
<ul class="submenu">
	<li>[<a href="<?= new Link("exemplo:adicionar") ?>">{{Adicionar novo Item}}</a>]</li>
</ul>
<? if (count($itens)){ ?>
<table width="100%">
	<tr>
		<th width="100">{{Sigla}}</th>
		<th>{{Nome}}</th>
		<th width="100">{{Ações}}</th>
	</tr>
	<? foreach($itens as $o){ ?>
	<tr class="item">
		<td><?= $o->sigla ?></a></td>
		<td><?= $o->nome ?></td>
		<td><a href="<?= new Link('exemplo:alterar', "id={$o->id}") ?>">{{Alterar}}</a> | <a class="confirm" title="{{Excluir item}}: <?= $o->nome ?>" href="<?= new Link('exemplo:excluir', "id={$o->id}") ?>">{{Excluir}}</a></td>
	</tr>
	<? } ?>
</table>

<? }else{ ?>
<h3>{{Nenhum orgão cadastrado}}</h3>
<? } ?>
