<?php
namespace quarsintex\quartronic\qcore;

class Quartronic extends QSource
{
    protected $mode;
    protected $user;
    protected $architect;

    const MODE_CONSOLE = 'console';
    const MODE_WEB = 'web';

    protected $params = [
        'webDir' => '',
        'webPath' =>  '/',
        'appDir' => '',
        'configDir' => __DIR__.'/../../../../config/',
        'runtimeDir' => __DIR__.'/../../../../runtime/',
        'returnRender' => false,
        'requireAuth' => true,
    ];

    protected function getConnectedProperties()
    {
        if (!$this->_connectedProperties) $this->_connectedProperties = [
            'router' => $this->architect->dynUnit('router'),
            'db' => $this->dynUnit(function() {
                return $this->sysDB;
            }),
            'externManager' => $this->architect->dynUnit('externManager'),
            'export' => $this->architect->dynUnit('export'),
            'render' => $this->architect->dynUnit('render'),
            'urlManager' => $this->architect->dynUnit('urlManager'),
        ];
        return $this->_connectedProperties;
    }

    function getQRootDir()
    {
        return __DIR__.'/../';
    }

    function getWebDir()
    {
        return $this->router->webDir;
    }

    function getWebPath()
    {
        return $this->router->webPath;
    }

    function getAppDir()
    {
        return $this->router->appDir;
    }

    function __construct($params=[])
    {
        self::$Q = $this;
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $customArchitecture = isset($this->params['customArchitecture']) ? $this->params['customArchitecture'] : [];
        $this->architect = new \quarsintex\quartronic\qcore\QArchitect($customArchitecture);
        $this->getConnectedProperties();
        $this->addConnectedProperty('sysDB', $this->dynUnit(function() {
            return $this->architect->initUnit('db');
        }));
        $this->externManager->initExtDirs();
    }

    function run($params=[])
    {
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $this->mode = isset($params['mode']) ? $params['mode'] : null;
        switch ($this->mode) {
            case self::MODE_CONSOLE:
                $this->addConnectedProperty('request', $this->dynUnit(function() {
                    return $this->architect->initUnit('consoleRequest');
                }));
                break;

            default:
                $this->mode = self::MODE_WEB;
                $this->addConnectedProperty('request', $this->dynUnit(function() {
                    return $this->architect->initUnit('webRequest');
                }));
                break;
        }
        return $this->router->run($this->request->route);
    }

    function getVersion()
    {
        return '0.2.40';
    }

    function getLastVersion()
    {
        $text = file_get_contents('https://raw.githubusercontent.com/Quardex/Quartronic/master/qcore/Quartronic.php');
        preg_match('/getVersion\(\)[^\']*\'([^\']*)\'/m', $text, $found);
        return isset($found[1]) ? $found[1] : 0;
    }

    function defineUser($user)
    {
        $this->user = $user;
    }
}

?>