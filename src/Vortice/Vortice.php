<?php

namespace Vortice;

use Vortice\Env;
use Vortice\Exception;
use Vortice\Dispatcher;

use Vortice\Exception\MalformatedUriException;

class Vortice {

    public $env;
    public $request;
    static $fw;

    public function __construct() {
        Exception::setup();
        $this->validateUri();
        self::$fw = $this;
        $this->env = new Env;
        $this->loadPath();
        $dispatch = new Dispatcher($this);
        $this->request = $dispatch->executeUri(URI);
    }

    public function loadPath() {
        define('VROOT', str_replace('src/bootstrap.php', '', $_SERVER['SCRIPT_NAME']));
        define('ROOT', str_replace('src/bootstrap.php', '', $_SERVER['SCRIPT_FILENAME']));
        define('URI', str_replace(VROOT, '/', $_SERVER['REQUEST_URI']));
        define('APP_DIR', ROOT . "app/");
    }

    private function validateUri() {
        if (!valid_uri($_SERVER['REQUEST_URI']))
            if ($_SERVER['REQUEST_METHOD'] === 'GET')
                redirect($_SERVER['REQUEST_URI'] . '/');
            else
                throw new MalformatedUriException('The uri must end with a slash (/)', 403);
    }

    public static function getFw() {
        return self::$fw;
    }

    public function render(){
        return (string)$this->request;
    }

    public function __toString(){
        return $this->render();
    }

}