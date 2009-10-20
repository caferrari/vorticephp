<?php
/* 
 * Copyright (c) 2008, Carlos André Ferrari <[carlos@]ferrari.eti.br>; Luan Almeida <[luan@]luan.eti.br>
 * All rights reserved. 
 */

/**
 * Sample framework controller
 * @package SampleApp
 * @subpackage Controller
 */
class ExemploController extends Controller{

	/**
	* Execute the action /exemplo
	*
	* @return	void
	*/
	public function index(){
		$this->itens = ExemploDAO::getList();
	}
	
	/**
	* Execute the action /exemplo/adicionar
	*
	* @return	void
	*/
	public function adicionar(){
		if (post)
		{
			$erros = array();
			foreach($_POST as $k => $v)
				$$k = is_array($v) ? $v : addslashes(htmlspecialchars($v));
			
			if ($sigla=='') $erros[] = "Digite a sigla do item";
			if ($nome=='') $erros[] = "Digite o nome do item";
			if (!count($erros))
			{
				ExemploDAO::insert(new Exemplo(NULL, $nome, $sigla));
				Post::setSucesso("Item adicionado com sucesso!", new Link("exemplo"));
			}
			else
				Post::setErros("Ocorreram os seguintes erros:", $erros);
		}
	}
	
	/**
	* Execute the action /exemplo/alterar/id:$id
	*
	* @param	int	$id
	* @return	void
	*/
	public function alterar(){
		$id = p("id");
		if (post)
		{
			$erros = array();
			foreach($_POST as $k => $v)
				$$k = is_array($v) ? $v : addslashes(htmlspecialchars($v));
			
			if ($sigla=='') $erros[] = "Digite a sigla do item";
			if ($nome=='') $erros[] = "Digite o nome do item";
			if (!count($erros))
			{
				ExemploDAO::update(new Exemplo($id, $nome, $sigla));
				Post::setSucesso("Item alterado com sucesso!", new Link("exemplo"));
			}
			else
				Post::setErros("Ocorreram os seguintes erros:", $erros);
		}
		
		Template::setVar('area', 'Alterar Item');
		Post::load(ExemploDAO::get($id));

		Template::setView("adicionar");
	}
	
	/**
	* Execute the action /exemplo/excluir/id:$id
	*
	* @param	int	$id
	* @return	void
	*/
	public function excluir(){
		$id = p('id');
		try {
			ExemploDAO::delete($id);
			Post::setSucesso("Item excluido com sucesso!", new Link("exemplo"));
		} catch (Exception $e) {
			Post::setErros($e->getMessage());
		}
	}

}
