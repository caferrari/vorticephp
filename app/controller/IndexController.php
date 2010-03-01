<?php

class IndexController extends Controller {

	public function index_GET(){
		Vortice::setTemplate('ha');
		$this->nome = 'andre';
		$this->_format = 'json';
	}

}
