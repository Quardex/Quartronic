<?php
namespace quarsintex\quartronic\qcore;

class Quartronic extends QSource
{
    protected $mode;

    protected $db;
    protected $request;
    protected $router;
    protected $render;
    protected $urlManager;
    protected $export;

    protected $user;

    const MODE_CONSOLE = 'console';
    const MODE_WEB = 'web';

    protected $params = [
        'webDir' => '',
        'webPath' =>  '/',
        'appDir' => '',
        'returnRender' => false,
        'requireAuth' => true,
    ];

    function getQRootDir()
    {
        return __DIR__.'/../';
    }

    function getWebDir()
    {
        return $this->params['webDir'];
    }

    function getWebPath()
    {
        return $this->params['webPath'];
    }

    function getAppDir()
    {
        return $this->params['appDir'];
    }

    function __construct($params=[])
    {
        self::$Q = new \quarsintex\quartronic\qcore\QArchitect($this);
        if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
        $this->db = self::$Q->getUnit('db');
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
        return '0.2.17';
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