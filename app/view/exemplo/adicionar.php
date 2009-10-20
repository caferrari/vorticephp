<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */
 
/**
 * Sample of a framework view to add or update a Exemplo field
 * @package SampleApp
 * @subpackage View
 */
?>
<h2>{{<?php echo (action=='adicionar') ? 'Adicionar Item' : 'Editar Item';  ?>}}</h2>
<span class="desc"><!--desc--></span>
<fieldset>
	<form method="post" action="<?php echo new Link("exemplo:" . action) ?>">
		<input type="hidden" id="id" name="id" value="<?php echo Post::getVal('id') ?>" />
		<p>
			<label for="titulo">{{Sigla}}:</label>
			<input id="sigla" name="sigla" value="<?php echo Post::getVal('sigla') ?>" size="30" />
		</p>
		<p>
			<label for="nome">{{Nome}}:</label>
			<input id="nome" name="nome" value="<?php echo Post::getVal('nome') ?>" size="50" />
		</p>	
		<p class="submit">
			<input type="submit" value="{{Enviar}}" /> {{ou}} <a href="<?php echo new Link('exemplo') ?>" title="{{Voltar para a página anterior}}">{{Voltar}}</a>
		</p>
	</form>
</fieldset>
