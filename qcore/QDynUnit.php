<?php
namespace quarsintex\quartronic\qcore;

class QDynUnit extends QSource
{
    protected $_closure;

    public function __construct($closure) {
        $this->_closure = $closure;
    }

    public function run() {
        $check = new \ReflectionFunction($this->_closure);
        if ($check->isClosure()) $this->_closure = ($this->_closure)();
        return $this->_closure;
    }
}

?>