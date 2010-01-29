<?php
/**
 * Sample of a framework controller /index or just /
 * @package SampleApp
 * @subpackage Controller
 */
class IndexController{

	/**
	* Execute the action: /index/index or just /
	*
	* @return	void
	*/
	public function index(){
		Vortice::setVar('area', 'Selecione uma Opção');
		Vortice::setVar('desc', '');
	}
}
