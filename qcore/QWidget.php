<?php
namespace quarsintex\quartronic\qcore;

class QWidget extends QSource
{
    protected $renderValues = [];

    public function __construct($params=[])
    {
        foreach ($params as $param => $value) {
            $this->$param = $value;
        }
        $this->name = basename(static::class);
        $this->run();
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->renderValues)) {
            return $this->renderValues[$name];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            parent::__set($name, $value);
        } else {
            $this->renderValues[$name] = $value;
        }
    }

    public function run() {}

    public function render()
    {
        foreach ($this->renderValues as $name => $value) {
            $$name = $value;
        }
        $widgetPath = self::$Q->qRootDir . 'qthemes/adminbsb/widgets/';
        ob_start();
        include($widgetPath.strtolower($this->name).'/index.php');
        return ob_get_clean();
    }
}

?>