<?php

namespace Vortice;

class Request {
    public $code        = 200;
    public $method      = '';
    public $module      = 'default';
    public $controller  = 'index';
    public $action      = 'index';
    public $view        = 'index:index';
    public $layout      = '';
    public $format      = 'html';
    public $pars        = array();
    public $vars        = array();
    public $contents    = '';

    public function __construct(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && $this->isJsonRequst()){
            $this->format = 'json';
        }
    }

    public function __toString(){
        return $this->contents;
    }

    private function isJsonRequest(){
        return
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest' &&
            isset($_SERVER['HTTP_ACCEPT']) &&
            $_SERVER['HTTP_ACCEPT'] == 'application/json';
    }
}