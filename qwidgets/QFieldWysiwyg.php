<?php
namespace quarsintex\quartronic\qwidgets;

class QFieldWysiwyg extends \quarsintex\quartronic\qwidgets\QFieldText
{
    public $editor = 'TinyMCE';

    public function getCount() {
        static $count;
        return ++$count;
    }
}

?>