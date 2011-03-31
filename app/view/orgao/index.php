<?php
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
	<li>[<a href="<?= new Link("orgao:adicionar") ?>">{{Adicionar novo Item}}</a>]</li>
</ul>
<?php if (count($itens)){ ?>
<table width="100%">
	<tr>
		<th width="100">{{Sigla}}</th>
		<th>{{Nome}}</th>
		<th width="100">{{Ações}}</th>
	</tr>
	<?php foreach($itens as $o): ?>
	<tr class="item">
		<td><?php echo $o->sigla ?></a></td>
		<td><?php echo $o->nome ?></td>
		<td>
     <a href="<?php echo new Link('orgao:alterar', "id={$o->id}") ?>">{{Alterar}}</a> | 
     <a class="confirm" title="{{Excluir item}}: <?php echo $o->nome ?>" href="<?php echo new Link('orgao:excluir', "id={$o->id}") ?>">{{Excluir}}</a> |
     <a href="<?php echo new Link('alternativa:index', "id_pergunta={$o->id}") ?>">{{Alternativas}}</a> |  
    </td>
	</tr>
	<?php endforeach; ?>
</table>

<?php }else{ ?>
<h3>{{Nenhum orgão cadastrado}}</h3>
<?php } ?>
