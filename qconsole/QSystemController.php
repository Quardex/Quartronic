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

    function actUpdate() {
        if (self::$Q->db->checkInit()) self::$Q->db->close();
        for ($i=0; $i<101; $i++) {
            echo "Preparing: ".$i."%\r";
            usleep(100000);
        }
        echo "\n";
        echo "Start updating...\n";
        file_put_contents(self::$Q->rootDir.'update.lock', 1);
        \quarsintex\quartronic\qcore\QUpdater::run(self::$Q->rootDir.'../../../');
    }

    function __destruct() {
        if ($this->action == 'update') {
            unlink(self::$Q->rootDir.'update.lock');
        }
    }
}

?>