<?php
namespace quarsintex\quartronic\qcore;

use PDO;

class Quartronic extends QSource
{
  protected $_dbList;

  protected $mode;

  protected $db;
  protected $request;
  protected $router;
  protected $render;
  protected $urlManager;
  protected $api;
  protected $user;

  const MODE_CONSOLE = 'console';
  const MODE_WEB = 'web';

  protected $params = [
    'dbSettingsType' => 0,
    'dbDir' => __DIR__.'/../q.db',
    'returnRender' => false,
    'webDir' => '',
    'webPath' =>  '/',
    'appDir' => '',
    'requireAuth' => true,
  ];

  function getRootDir()
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

  function defineUser($user)
  {
      $this->user = $user;
  }

  function __construct($params=[])
  {
    self::$Q = new \quarsintex\quartronic\qcore\QArchitect($this);
    if ($params && is_array($params)) $this->params = array_merge($this->params, $params);
    $this->_dbList[] = new \PDO('sqlite:' . $this->params['dbDir'],null,null,
      array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ));
    $this->db = self::$Q->getUnit('db', [$this->_dbList[$this->params['dbSettingsType']]]);
    $this->router = self::$Q->getUnit('router');
    $this->api = self::$Q->getUnit('api');
  }

  function __destruct()
  {
    $file = sys_get_temp_dir().'\\'.$this->getSCache(1);
    if (file_exists($file)) unlink($file);
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

  function getVersion() {
    return '0.2.3';
  }
}

?>