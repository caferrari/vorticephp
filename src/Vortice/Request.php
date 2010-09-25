<?php

namespace Vortice;

class Request {
    public $module      = 'default';
    public $controller  = 'index';
    public $action      = 'index';
    public $view        = 'index:index';
    public $layout      = '';
    public $format      = 'html';
    public $code        = '200';
    public $pars        = array();
    public $vars        = array();
    public $contents    = '';

    public function __toString(){
        return $this->contents;
    }
}