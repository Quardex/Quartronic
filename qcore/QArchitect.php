<?php
namespace quarsintex\quartronic\qcore;

class QArchitect extends QSource
{
  protected $_Q;

  function getArchitecture() {
    return [
      'db' => '\quarsintex\quartronic\qcore\QPdo',
      'router' => '\quarsintex\quartronic\qcore\QRouter',
      'render' => '\quarsintex\quartronic\qcore\QRender',
      'consoleRequest' => '\quarsintex\quartronic\qcore\QConsoleRequest',
      'webRequest' => '\quarsintex\quartronic\qcore\QWebRequest',
      'urlManager' => '\quarsintex\quartronic\qcore\QUrlManager',
    ];
  }

  function __construct($Q)
  {
    $this->_Q = $Q;
  }

  function __call($name, $arguments=[])
  {
    return call_user_func_array([$this->_Q, $name], $arguments);
  }

  function __get($name)
  {
    return isset($this->_Q->$name) ? $this->_Q->$name : parent::__get($name);
  }

  function __isset($name) {
    return  property_exists($this->_Q, $name);
  }

  function getUnit($name, $params=[])
  {
    $arguments = $attributes = [];
    foreach ($params as $key => $value)
        is_int($key) ? $arguments[] = $value : $attributes[$key] = $value;
    $refClass = new \ReflectionClass($this->architecture[$name]);
    $obj = $refClass->newInstanceArgs($arguments);
    foreach ($attributes as $name => $value) {
        $obj->$name = $value;
    }
    return $obj;
  }


  function getConst($name) {
    return constant(get_class($this->_Q).'::'.$name);
  }
}

?>