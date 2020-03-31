<?php
namespace quarsintex\quartronic\qcore;

class QController extends QSource
{
    protected $action;

    function __construct($action) {
        if (is_array($action)) {
            $action = ucfirst($action[1]);
        }
        $this->action = $action;
    }

    function redirect($target) {
        header('Location: '.$target, true, 303);
        exit;
    }
}

?>