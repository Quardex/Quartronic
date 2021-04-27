<?php
namespace quarsintex\quartronic\qcore;

class QRelation extends QDynUnit
{
    public $target;

    public function __construct($closure, $target) {
        parent::__construct($closure);
        $this->target = $target;
    }

    public function __toString()
    {
        return $this->run()->{$this->target};
    }
}

?>