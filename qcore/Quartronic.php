<?php
namespace quarsintex\quartronic\qcore;

class Quartronic extends QSource
{
    protected $mode;

    protected $sysDB;
    protected $db;
    protected $request;
    protected $router;
    protected $render;
    protected $urlManager;
    protected $export;
    protected $externManager;

    protected $user;

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
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $customArchitecture = isset($this->params['customArchitecture']) ? $this->params['customArchitecture'] : [];
        self::$Q = new \quarsintex\quartronic\qcore\QArchitect($this, $customArchitecture);
        $this->sysDB = self::$Q->getUnit('db', ['sqlite:'.$this->params['runtimeDir'].'q.db:sys']);
        $this->db = false ? self::$Q->getUnit('db') : $this->sysDB;
        ($this->externManager = self::$Q->getUnit('externManager'))->initExtDirs();
        $this->router = self::$Q->getUnit('router');
        $this->export = self::$Q->getUnit('export');
    }

    function run($params=[])
    {
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $this->render = self::$Q->getUnit('render');
        $this->urlManager = self::$Q->getUnit('urlManager');
        $this->mode = isset($params['mode']) ? $params['mode'] : null;
        switch ($this->mode) {
            case self::MODE_CONSOLE:
                $this->request = self::$Q->getUnit('consoleRequest');
                break;

            default:
                $this->mode = self::MODE_WEB;
                $this->request =  self::$Q->getUnit('webRequest');
                break;
        }
        return $this->router->run($this->request->route);
    }

    function getVersion()
    {
        return '0.2.26';
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