<?php
namespace quarsintex\quartronic\qcore;

class QRouter extends QSource
{
    protected $controller;
 	protected $route;

    protected function getConnectedParams()
    {
        return [
            'returnRender' => &self::$Q->params['returnRender'],
            'appDir' => &self::$Q->params['appDir'],
            'webPath' => &self::$Q->webPath,
            'mode' => &self::$Q->mode,
            'rootDir' => &self::$Q->rootDir,
        ];
    }

    public function getRouteDir($key = '') {
        $list = [
            self::$Q->getConst('MODE_WEB') => 'qcontrollers',
            self::$Q->getConst('MODE_CONSOLE') => 'qconsole',
        ];
        return $key ? $list[$key] : $list;
    }

    public function getDefaultController($key = '') {
        $list = [
            self::$Q->getConst('MODE_WEB') => 'site',
            self::$Q->getConst('MODE_CONSOLE') => 'qSystem',
        ];
        return $key ? $list[$key] : $list;
    }

    function run($route = '') {
        return $this->execute($route);
    }

    function execute($route)
    {
        if (!is_array($route)) $route = explode('/', $route);
        if (empty($route[0])) $route[0] = $this->getDefaultController($this->mode);
        if (empty($route[1])) $route[1] = 'index';
        $this->route = strtolower(implode('/', $route));
        $routeDir = $this->getRouteDir(self::$Q->mode);
        $controllerName = ucfirst($route[0]).'Controller';
        $controllerClass = $routeDir.'\\'.$controllerName;
        if ($this->appDir) {
            $routeDir = $this->appDir.'/'.$routeDir;
            $controllerClass = basename($this->appDir).'\\'.$controllerClass;
        } else {
            $controllerClass = '\\quarsintex\\quartronic\\' . $controllerClass;
        }
        if (self::$Q->mode == self::$Q->getConst('MODE_CONSOLE')) \quarsintex\quartronic\qcore\QConsoleController::init();
        $controllerPath = $this->rootDir.$routeDir.'/'.$controllerName.'.php';
        if (file_exists($controllerPath)) {
            if (!class_exists($controllerClass)) require_once($controllerPath);
            $this->controller = new $controllerClass(ucfirst($route[1]));
            $methodName = 'act' . $this->controller->action;
            if (method_exists($this->controller, $methodName)) {
                return $this->controller->$methodName();
            } else {
                $e404 = true;
            }
        } else {
            $e404 = true;
        }
        if (!empty($e404)) {
            switch (self::$Q->mode) {
                case self::$Q->getConst('MODE_CONSOLE'):
                    echo "Action not found";
                    break;

                case self::$Q->getConst('MODE_WEB'):
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>');
                    break;
            }
            exit;
        }
    }
}

?>
