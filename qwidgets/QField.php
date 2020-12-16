<?php
namespace quarsintex\quartronic\qwidgets;

class QField extends \quarsintex\quartronic\qcore\QWidget
{
    public $key;
    public $value;
    public $type = 'varchar';
    public $required = false;
    public $error;
}

?>