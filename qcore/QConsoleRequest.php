<?php
namespace quarsintex\quartronic\qcore;

class QConsoleRequest extends QSource
{
    protected $route = '';
    protected $params;
    protected $time;


    function __construct()
    {
        global $argv;
        $this->time = time();
        unset($argv[0]);
        foreach ($argv as $param) {
            preg_match('/^--([^=]*)=(.*)/', $param, $found);
            if ($found) {
                $this->params[$found[1]] = $found[2];
            } else {
                $this->params[] = $param;
            };
        }
        if (!empty($this->params[0])) $this->route = $this->params[0];
    }

}

?>
