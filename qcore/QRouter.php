<?php
namespace
{
    class NotFoundException extends Exception {}
}

namespace quarsintex\quartronic\qcore
{
    class QRouter extends QSource
    {
        protected $controller;
        protected $route;
        protected $_appDir;

        protected function getConnectedProperties()
        {
            return [
                'returnRender' => &self::$Q->params['returnRender'],
                'globalAppDir' => &self::$Q->params['appDir'],
                'webPath' => &self::$Q->params['webPath'],
                'subWebPath' => &self::$Q->params['subWebPath'],
                'webDir' => &self::$Q->params['webDir'],
                'configDir' => &self::$Q->externManager->configDir,
                'runtimeDir' => &self::$Q->externManager->runtimeDir,
                'mode' => &self::$Q->mode,
                'autoStructure' => \quarsintex\quartronic\qcore\QCrud::getAutoStructure(),
            ];
        }

        public function getAppDir()
        {
            return $this->_appDir ? $this->_appDir : $this->globalAppDir;
        }

        public function setAppDir($value)
        {
            $this->_appDir = $value;
        }

        public function getRouteDir($key = '')
        {
            $list = [
                self::$Q::MODE_WEB => 'qcontrollers',
                self::$Q::MODE_CONSOLE => 'qconsole',
            ];
            return $key ? $list[$key] : $list;
        }

        public function getDefaultController($key = '')
        {
            $list = [
                self::$Q::MODE_WEB => 'site',
                self::$Q::MODE_CONSOLE => 'qSystem',
            ];
            return $key ? $list[$key] : $list;
        }

        function run($route = '')
        {
            try {
                return $this->execute($route);
            } catch (\NotFoundException $e) {
                header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
                return (new \quarsintex\quartronic\qcontrollers\SiteController('404'))->act404();
            }
        }

        function execute($route)
        {
            if ($this->mode != self::$Q::MODE_CONSOLE && strpos('/'.$route, $this->webPath) === 0) $route = substr($route, strlen($this->webPath)-1);
            if (!is_array($route)) $route = explode('/', $route);
            if (empty($route[0])) $route[0] = $this->getDefaultController($this->mode);
            if (empty($route[1])) $route[1] = 'index';
            $this->route = strtolower(implode('/', $route));
            $routeDir = $this->getRouteDir(self::$Q->mode);
            $controllerName = ucfirst($route[0]) . 'Controller';
            $controllerClass = $routeDir . '\\' . $controllerName;
            if ($this->appDir) {
                $routeDir = '../../../' . $this->appDir . '/' . $routeDir;
                $controllerClass = basename($this->appDir) . '\\' . $controllerClass;
            } else {
                $controllerClass = '\\quarsintex\\quartronic\\' . $controllerClass;
            }
            if ($this->mode == self::$Q::MODE_CONSOLE) \quarsintex\quartronic\qcore\QConsoleController::init();
            $controllerPath = $this->qRootDir . $routeDir . '/' . $controllerName . '.php';
            if (file_exists($controllerPath) || $this->checkAutoRoute($controllerClass, $route)) {
                if (!class_exists($controllerClass)) require_once($controllerPath);
                $this->controller = new $controllerClass($route);
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
                    case self::$Q::MODE_CONSOLE:
                        echo "Action not found\n";
                        break;

                    case self::$Q::MODE_WEB:
                        throw new \NotFoundException(404);
                        break;
                }
                exit;
            }
        }

        function checkAutoRoute(&$controllerClass, &$route)
        {
            if ($this->mode == self::$Q::MODE_WEB)
                foreach ($this->autoStructure as $section => $data) {
                    if ($section == $route[0]) {
                        $controllerClass = '\\quarsintex\\quartronic\\qcore\\QCrudController';
                        return true;
                    }
                }
            return false;
        }

        function getQRootDir()
        {
            return __DIR__.'/../';
        }

        public function initExtDirs()
        {
        }
    }
}

?>