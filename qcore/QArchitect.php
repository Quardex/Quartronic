<?php
namespace quarsintex\quartronic\qcore;

class QUnit extends QSource
{
    protected $_unit;
    protected $_class;
    protected $_params;

    function __construct($class, $params=[])
    {
        $this->_class = $class;
        $this->_params = $params;
    }

    protected function init()
    {
        $arguments = $attributes = [];
        foreach ($this->_params as $key => $value)
            is_int($key) ? $arguments[] = $value : $attributes[$key] = $value;
        $refClass = new \ReflectionClass($this->_class);
        $obj = $refClass->newInstanceArgs($arguments);
        foreach ($attributes as $name => $value) {
            $obj->$name = $value;
        }
        return $obj;
    }

    protected function getUnit()
    {
        if (!$this->_unit) $this->_unit = $this->init();
        return $this->_unit;
    }

    public function checkInit()
    {
        return !empty($this->_unit);
    }

    function __call($name, $arguments=[])
    {
        return call_user_func_array([$this->getUnit(), $name], $arguments);
    }

    function __isset($name)
    {
        return isset($this->getUnit()->$name);
    }

    function __get($name)
    {
        return $this->getUnit()->$name;
    }
}

class QArchitect extends QSource
{
    protected $_Q;

    function getArchitecture()
    {
        return [
            'db' => '\quarsintex\quartronic\qcore\QPdo',
            'router' => '\quarsintex\quartronic\qcore\QRouter',
            'render' => '\quarsintex\quartronic\qcore\QRender',
            'consoleRequest' => '\quarsintex\quartronic\qcore\QConsoleRequest',
            'webRequest' => '\quarsintex\quartronic\qcore\QWebRequest',
            'urlManager' => '\quarsintex\quartronic\qcore\QUrlManager',
            'api' => '\quarsintex\quartronic\qcore\QApi',
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

    function __isset($name)
    {
        return  property_exists($this->_Q, $name);
    }

    function getUnit($name, $params=[])
    {
        return new QUnit($this->architecture[$name], $params);
    }

    function getConst($name)
    {
        return constant(get_class($this->_Q).'::'.$name);
    }
}

?>