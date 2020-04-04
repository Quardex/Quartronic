<?php
namespace quarsintex\quartronic\qcontrollers;

class SiteController extends \quarsintex\quartronic\qcore\QController
{
    function actIndex() {
        return self::$Q->render->run('site/index');
    }

    function act404() {
        return self::$Q->render->run('',[],'404');
    }

    function act500() {
        return self::$Q->render->run('',[],'500');
    }
}

?>
