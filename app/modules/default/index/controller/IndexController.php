<?php

class IndexController extends Vortice\Controller {

    public function index(){
        $this->_view = 'huhu';
        $this->_format = 'json';
        $this->nome = "andré";
    }

}
