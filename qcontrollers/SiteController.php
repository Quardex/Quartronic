<?php
namespace quarsintex\quartronic\qcontrollers;

class SiteController extends \quarsintex\quartronic\qcore\QController
{
    function actIndex() {
        return self::$Q->render->run('site/index');
    }
}

?>
