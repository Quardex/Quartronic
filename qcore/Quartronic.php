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
  protected $api;
  protected $user;

  const MODE_CONSOLE = 'console';
  const MODE_WEB = 'web';

  protected $params = [
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
      $this->db = self::$Q->getUnit('db');
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
      return '0.2.7';
  }

  function getLastVersion()
  {
      $text = file_get_contents('https://raw.githubusercontent.com/Quardex/Quartronic/master/qcore/Quartronic.php');
      preg_match('/getVersion\(\)[^\']*\'([^\']*)\'/m', $text, $found);
      return isset($found[1]) ? $found[1] : 0;
  }

}

?>