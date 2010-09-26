<?php

namespace Vortice;

class Link{

    private $link;

    public function __construct($action, $pars=array()){
        $path = str_replace(':', '/', $action);
        $this->link = VROOT . $path . str_replace('&', '/', str_replace('=', ':', http_build_query($pars)));
    }

    public function __toString(){
        return $this->link;

    }

}