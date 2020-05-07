<?php
namespace quarsintex\quartronic\qcore;

class QGlobalSingleton extends QSource
{
    static function app() {
        return self::$Q;
    }
}

?>