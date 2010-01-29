<?php
/**
 * Sample framework controller
 * @package SampleApp
 * @subpackage Controller
 */
class OrgaoController extends Controller{

	/**
	* Execute the action /exemplo
	*
	* @return	void
	*/
	public function index(){
		$this->itens = Orgao::all();
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

			$orgao = Post::toObject();

			if ($orgao->sigla == '') $erros['sigla'] = "Digite a sigla do item";
			if ($orgao->nome == '') $erros['nome'] = "Digite o nome do item";
			if (!count($erros))
			{
				$orgao->insert('sigla, nome');
				Post::success("Item adicionado com sucesso!", new Link("orgao"));
			}
			else
				Post::error("Ocorreram os seguintes erros:", $erros);
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

			$orgao = Post::toObject();
			
			if ($orgao->sigla == '') $erros['sigla'] = "Digite a sigla do item";
			if ($orgao->nome == '') $erros['nome'] = "Digite o nome do item";
			
			if (!count($erros))
			{
				$orgao->save('sigla, nome');
				Post::success("Item alterado com sucesso!", new Link("orgao"));
			}
			else
				Post::error("Ocorreram os seguintes erros:", $erros);
		}
		
		Vortice::setVar('area', 'Alterar Item');
		Post::load(Orgao::load($id, 'orgao'));
		$this->_view = "adicionar";
	}
	
	/**
	* Execute the action /exemplo/excluir/id:$id
	*
	* @param	int	$id
	* @return	void
	*/
	public function excluir(){
		$orgao = Post::toObject();
		try {
			$orgao->delete();
			Post::success("Item excluido com sucesso!", new Link("orgao"));
		} catch (Exception $e) {
			Post::error($e->getMessage());
		}
	}

}
