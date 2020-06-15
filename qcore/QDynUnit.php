<?php
namespace quarsintex\quartronic\qcore;

class QDynUnit extends QSource
{
    protected $_closure;

    public function __construct($closure) {
        $this->_closure = $closure;
    }

    public function run() {
        $closure = $this->_closure;
        return $closure();
    }
}

?>