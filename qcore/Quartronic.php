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
        'subWebPath' => '',
        'appDir' => '',
        'configDir' => __DIR__.'/../../../../config/',
        'runtimeDir' => __DIR__.'/../../../../runtime/',
        'route' => '',
        'returnRender' => false,
        'requireAuth' => true,
        'db' => [
//          'driver'    => 'sqlite',
//          'database'  => $this->dbDir.'q.db',
//          'driver'    => 'mysql',
//          'database'  => 'testbase',
//          'host'      => 'localhost',
//          'username'  => 'root',
//          'password'  => '',
//          'charset'   => 'utf8',
//          'prefix'    => '',
//          'collation' => 'utf8mb4_unicode_ci',
        ],
    ];

    protected function loadConnectedProperties()
    {
        $this->_connectedProperties = [
            'sysDB'=> $this->architect->dynUnit('db'),
            'db' => $this->architect->dynUnit('db', [$this->params['db']]),
            'router' => $this->architect->dynUnit('router'),
            'externManager' => $this->architect->dynUnit('externManager'),
            'export' => $this->architect->dynUnit('export'),
            'render' => $this->architect->dynUnit('render'),
            'urlManager' => $this->architect->dynUnit('urlManager'),
        ];
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
        $this->loadConnectedProperties();
        $this->externManager->initExtDirs();
    }

    function run($params=[])
    {
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $this->mode = isset($params['mode']) ? $params['mode'] : null;
        switch ($this->mode) {
            case self::MODE_CONSOLE:
                $this->addConnectedProperty('request', $this->architect->dynUnit('consoleRequest'));
                break;

            default:
                $this->mode = self::MODE_WEB;
                $this->addConnectedProperty('request', $this->architect->dynUnit('webRequest'));
                break;
        }
        return $this->router->run($this->params['route'] ?: $this->request->route);
    }

    function getVersion()
    {
        return '0.2.66';
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