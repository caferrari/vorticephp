<?php

namespace Vortice;

use Vortice\Request;
use Vortice\Exception\LayoutNotFoundException;
use Vortice\Exception\UnknowRenderFormatException;

use \DirectoryIterator;

class Layout {

    private $layouts;
    private $selected = false;

    public function __construct(){
        $this->loadTemplates();
    }
    
    public function loadTemplates(){
        $this->layouts = array();
        foreach (new DirectoryIterator(APP_DIR . 'layouts') as $tpl){
            if ($tpl->isDot()) continue;
            $this->layouts[$tpl->getBasename('.php')] = $tpl->getPathname();
        }

        if (count($this->layouts) === 1) list($this->selected) = array_keys($this->layouts);
    }

    public function getLayouts(){
        return $this->layouts;
    }

    public function render(Request $r){

        if ($r->format == 'html' && $r->layout !== false){
            if ($r->layout !== ''){
                if (!in_array($r->layout, $this->getLayouts())){
                    throw new LayoutNotFoundException("Layout: {$r} not found!");
                }
                $this->selected = $r->layout;
            }
            $layout = file_get_contents($this->layouts[$this->selected]);
            return str_replace('<!--content-->', $r->contents, $layout);
        }

        return $this->renderByFormat($r);
    }

    public function renderByFormat(Request $r){

        switch ($r->format){

            case 'html':
                return $r->contents;
            case 'json':
                return json_encode($r);
            default:
                throw new UnknowRenderFormatException($r->format);
        }

    }

}
?>
