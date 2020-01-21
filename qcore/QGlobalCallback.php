<?php
namespace quarsintex\quartronic\qcore;

class QGlobalCallback extends QSource
{
    static function app() {
        return self::$Q;
    }
}
?>