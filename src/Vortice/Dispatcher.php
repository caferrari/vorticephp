<?php

namespace Vortice;

use Vortice\Vortice;
use Vortice\Request;
use Vortice\Exception\MalformatedUriException;
use Vortice\Exception\ModuleNotFoundException;
use Vortice\Exception\ControllerNotFoundException;
use Vortice\Exception\ActionNotFoundException;

class Dispatcher {

    private $fw;
    public static $masterload = false;

    private function &loadPars($uri) {
        preg_match_all('@(([a-z0-9\-\_]+):([^/]*))@', $uri, $match, PREG_SET_ORDER);
        $pars = array();
        foreach ($match as $m)
            $pars[$m[2]] = $m[3];
        $_POST = array_merge($pars, $_POST);
        return $_POST;
    }

    public function __construct(Vortice $v) {
        $this->fw = $v;
    }

    public function decomposeRequest($uri) {
        
        $request = new Request;
        $request->pars = &$this->loadPars($uri);

        if ($uri === '/') {
            return $request;
        }

        if (!preg_match('@^/([a-z0-9\-_]+/){0,3}([a-z0-9\-_]+:[^/]+/)*$@', $uri))
            throw new MalformatedUriException('Invalid URI format!', 404);

        if (preg_match('@^/([a-z0-9_\-]+)/([a-z0-9_\-]+)/([a-z0-9_\-]+)/@', $uri, $match)) {
            $request->module        = $match[1];
            $request->controller    = $match[2];
            $request->action        = $match[3];
        } elseif (preg_match('@^/([a-z0-9_\-]+)/([a-z0-9_\-]+)/@', $uri, $match)) {
            $request->controller    = $match[1];
            $request->action        = $match[2];
        } elseif (preg_match('@^/([a-z0-9_\-]+)/@', $uri, $match))
            $request->controller    = $match[1];

        $request->view = $request->controller . ':' . $request->action;
        $this->view = $request->view;

        return $request;
    }

    public function executeUri($uri){
        return $this->execRequest($this->decomposeRequest($uri));
    }

    public function checkModule(Request $r){
        $this->moduleDir = APP_DIR . 'modules/' . $r->module;
        if (!is_dir($this->moduleDir)){
            throw new ModuleNotFoundException("Module: {$r->module} not found!");
        }
    }

    public function loadController(Request $r){
        $controller = camelize($r->controller) . 'Controller';
        $controllerPath = $this->moduleDir . '/' . $r->controller . '/controller/' . $controller . '.php' ;
        if (!file_exists($controllerPath)){
            throw new ControllerNotFoundException();
        }
        require_once $controllerPath;

        if (!class_exists($controller)){
            throw new UrongControllerNameException("class \"$controller\" not found inside the \"$controllerPath\" file");
        }
        return new $controller($r);
    }

    public function loadView($r){
        $view = str_replace(':', '/view/', $r->view);
        $viewPath = $this->moduleDir . '/' . $view . '.php' ;

        if (file_exists($viewPath)){
            ob_start();
            extract($r->vars);
            include ($viewPath);
            $r->contents = ob_get_clean();
        }
        
    }

    public function execRequest(Request $r){
        $this->checkModule($r);
        $controller = $this->loadController($r);
        $action = camelize($r->action);
        
        if (!method_exists($controller, $action)){
            throw new ActionNotFoundException();
        }

        ob_start();
        $controller->$action();
        $debug = ob_get_clean();

        if ($debug !== ''){
            die ($debug);
        }

        $this->loadView($r);

        return $r;
    }

}
