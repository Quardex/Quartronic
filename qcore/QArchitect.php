<?php
namespace quarsintex\quartronic\qcore;

class QArchitect extends QSource
{
    public $architecture;

    public function __construct($customArchitecture) {
        $this->architecture = array_merge($this->getDefaultArchitecture(), $customArchitecture);
    }

    public function getDefaultArchitecture()
    {
        return [
            'db' => '\quarsintex\quartronic\qcore\QPdo',
            'router' => '\quarsintex\quartronic\qcore\QRouter',
            'render' => '\quarsintex\quartronic\qcore\QRender',
            'consoleRequest' => '\quarsintex\quartronic\qcore\QConsoleRequest',
            'webRequest' => '\quarsintex\quartronic\qcore\QWebRequest',
            'urlManager' => '\quarsintex\quartronic\qcore\QUrlManager',
            'export' => '\quarsintex\quartronic\qcore\QExport',
            'externManager' => '\quarsintex\quartronic\qcore\QExternManager',
        ];
    }

    public function initUnit($name, $params=[])
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

    public function dynUnit($uid)
    {
        return parent::dynUnit(function() use($uid) {
            return $this->initUnit($uid);
        });
    }
}

?>