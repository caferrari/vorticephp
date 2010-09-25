<?php

namespace Vortice;

use Vortice\Request;
use Vortice\Interfaces\Controllable;

abstract class Controller implements Controllable {

    private $request;

    public function __construct(Request $r){
        $this->request = $r;
    }

    public function __set($prop, $val){
        if (substr($prop, 0, 1) == '_'){
            $method = '_set' . ucfirst(substr($prop, 1));
            if (method_exists($this, $method)){
                $this->$method($val);
            }
        }else{
            $this->request->vars[$prop] = $val;
        }
    }

    private function _setView($v){
        if (strstr($v, ':') !== false)
            $this->request->view = $v;
        else
            $this->request->view = preg_replace("@:[a-zA-Z0-9]+$@", ":$v", $this->request->view);
    }

}