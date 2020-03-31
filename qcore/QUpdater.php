<?php
namespace quarsintex\quartronic\qcore;

class QUpdater extends QSource
{
    protected $action;

    static function checkVersion() {
        $text = file_get_contents('https://raw.githubusercontent.com/Quardex/Quartronic/master/qcore/Quartronic.php');
        preg_match('/getVersion\(\)[^\']*\'([^\']*)\'/m',$text,$found);
        return isset($found[1]) ? $found[1] : 0;
    }
}

?>