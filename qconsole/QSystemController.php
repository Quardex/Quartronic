<?php
namespace quarsintex\quartronic\qconsole;

class QSystemController extends \quarsintex\quartronic\qcore\QConsoleController
{
    function actIndex()
    {
        echo 'Ready';
    }

    function actMigrate()
    {
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

    function actRestructDB()
    {
        \quarsintex\quartronic\qcore\QCrud::restructDB(true);
    }

    function actUpdate()
    {
        if (self::$Q->db->checkInit()) self::$Q->db->close();
        for ($i=0; $i<101; $i++) {
            echo "Preparing: ".$i."%\r";
            usleep(100000);
        }
        echo "\n";
        echo "Start updating...\n";
        \quarsintex\quartronic\qcore\QUpdater::run(self::$Q->qRootDir.'../../../');
    }
}

?>