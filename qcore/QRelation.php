<?php
namespace quarsintex\quartronic\qcore;

class QRelation extends QDynUnit
{
    public $target;
    protected $titleField;

    public function __construct($closure, $target, $titleField = '') {
        parent::__construct($closure);
        $this->target = $target;
        $this->titleField = $titleField;
    }

    public function __toString()
    {
        if (!is_string($this->run()->{$this->target})) return '';
        return $this->run()->{$this->target};
    }
}

?>