<?php
namespace quarsintex\quartronic\qconsole;

class QSystemController extends \quarsintex\quartronic\qcore\QConsoleController
{
    function actIndex() {
        echo 'OK';
    }

    function actMigrate() {
        global $argv;
        if (empty($argv[2])) $argv[2] = '';
        $migrator = new \quarsintex\quartronic\qcore\QMigrator();
        switch ($argv[2]) {
            case 'down':
                $migrator->down();
                break;

            default:
                $migrator->up();
                break;
        }
    }

    function actRestructDB() {
        \quarsintex\quartronic\qcore\QCrud::autostructDB();
    }
}

?>