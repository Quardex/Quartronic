<?php
namespace quarsintex\quartronic\qwidgets;

class QDropdown extends \quarsintex\quartronic\qwidgets\QField
{
    public $rows = 8;
    public $autoHeight = true;
    public $type = 'integer';

    public $options = [];

    protected $_current;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->setCurrent($this->value);
    }

    public function getCurrent()
    {
        if ($this->_current === null) return '';
        return $this->_current;
    }

    public function setCurrent($key)
    {
        if (array_key_exists($key, $this->options) || $key === '') {
            $this->_current = $key;
        } elseif ($key) {
            throw new \Exception('Value not exists in options');
        }

    }
}

?>