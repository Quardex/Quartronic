<?php
namespace quarsintex\quartronic\qcore;

class QConsoleController extends QController
{
    static function init()
    {
        echo "\nQuartronic CMS Console | v.".self::$Q->version."\n";
        echo '---------------------------'.preg_replace('/./', '-', self::$Q->version)."\n";
    }

    function __construct($action)
    {
        parent::__construct($action);
        if (method_exists($this, 'act'.$action)) echo 'Initialization: '.self::$Q->router->route."\n";
    }
}

?>