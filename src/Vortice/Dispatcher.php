<?php

namespace Vortice;

use Vortice\Vortice;
use Vortice\Request;
use Vortice\Exception\MalformatedUriException;
use Vortice\Exception\ModuleNotFoundException;
use Vortice\Exception\ControllerNotFoundException;
use Vortice\Exception\ActionNotFoundException;
use Vortice\Link;

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

        $module = $controller = $action = '';

        if (preg_match('@^/([a-z0-9_\-]+)/([a-z0-9_\-]+)/([a-z0-9_\-]+)/@', $uri, $match)) {
            $module        = $match[1];
            $controller    = $match[2];
            $action        = $match[3];
        } elseif (preg_match('@^/([a-z0-9_\-]+)/([a-z0-9_\-]+)/@', $uri, $match)) {
            $controller    = $match[1];
            $action        = $match[2];
        } elseif (preg_match('@^/([a-z0-9_\-]+)/@', $uri, $match))
            $controller    = $match[1];

        if ($request->method == 'GET'){
            $uri = $this->checkDefaults($controller, $action, $request->pars);
            if ($uri) redirect($uri);
        }
                
        $request->module     = $module ?: 'default';
        $request->controller = $controller ?: 'index';
        $request->action     = $action ?: 'index';

        $request->view = $request->controller . ':' . $request->action;
        $this->view = $request->view;

        return $request;
    }

    public function checkDefaults($controller, $action, $pars){
        if ($action == 'index' || $action == ''){
            if ($controller == 'index'){
                return (string)new Link('', $pars);
            }
            return (string)new Link($controller, $pars);
        }
        return false;
    }

    public function executeUri($uri){
        return $this->execRequest($this->decomposeRequest($uri));
    }

    public function checkModule(Request $r){
        $this->moduleDir = APP_DIR . 'modules/' . $r->module;
        if (!is_dir($this->moduleDir)){
            throw new ModuleNotFoundException("Module: \"{$r->module}\" not found!");
        }
    }

    public function loadController(Request $r){
        $controller = camelize($r->controller) . 'Controller';
        $controllerPath = $this->moduleDir . '/' . $r->controller . '/controller/' . $controller . '.php' ;
        if (!file_exists($controllerPath)){
            throw new ControllerNotFoundException("Controller \"{$r->module}/{$r->controller}\" not found!");
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
            throw new ActionNotFoundException("Action \"{$r->module}/{$r->controller}:{$r->action}\" not found!");
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
