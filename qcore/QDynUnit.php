<?php
namespace quarsintex\quartronic\qcore;

class QDynUnit extends QSource
{
    protected $_closure;

    public function __construct($closure) {
        $this->_closure = $closure;
    }

    public function run() {
        if ($this->_closure instanceof \Closure) $this->_closure = ($this->_closure)();
        return $this->_closure;
    }
}

?>