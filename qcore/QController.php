<?php
namespace quarsintex\quartronic\qcore;

class QController extends QSource
{
    protected $action;

    function __construct($action) {
        $this->action = $action;
    }

    function redirect($target) {
        header('Location: '.$target, true, 303);
        exit;
    }
}

?>