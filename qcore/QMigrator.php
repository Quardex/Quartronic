<?php
namespace quarsintex\quartronic\qcore;

use \quarsintex\quartronic\qmodels\QMigration;

class QMigrator extends QSource
    {
    public function up()
    {
        $this->run('up');
    }

    public function down()
    {
        $this->run('down');
    }

    protected function run($mode='up')
    {
        if ($mode == 'up') {
            $list = $this->newMigrations;
            $type = 'New';
            $act = 'Run';
        } else {
            $list = $this->oldMigrations;
            $type = 'Next';
            $act = 'Revert';
        }
        if ($list) {
            echo "\n".$type." migrations has been detected:\n";
            foreach($list as $migration) {
                echo $migration."\n";
            }
            echo "\n".$act." this migrations? [Yes/No]\n";
            $answer = trim(fgets(STDIN));
            if (strtolower($answer[0])=='y') {
                $this->executeMigrations($list, $mode);
            }
        } else {
            echo "\n".($type == 'New' ? 'New m' : 'M').'igrations not found';
        }
    }

    protected function getFileList()
    {
        $entries = scandir(self::$Q->qRootDir.'qmigrations');
        $list = [];
        foreach($entries as $entry) {
            if (strpos($entry, ".") !== 0) {
                $list[preg_replace('/(.*).php$/', '$1', $entry)] = true;
            }
        }
        return $list;
    }

    protected function getNewMigrations($count=0)
    {
        $list = $this->getFileList();
        try {
            foreach (QMigration::findAll() as $migration) {
                if (isset($list[$migration->name])) unset($list[$migration->name]);
            }
        } catch(\Exception $e) {}
        $list = array_keys($list);
        if ($count) {
            for ($i=0;$i<count;$i++) {
                $temp[] = $list[$i];
            }
            $list = $temp;
        }
        return $list;
    }

    protected function getOldMigrations($count=1)
    {
        $temp = [];
        $list = $this->getFileList();
        foreach (QMigration::findAll() as $migration) {
            if (isset($list[$migration->name])) $temp[$migration->name] = true;
        }
        $list = array_keys($temp);
        $temp = [];
        if ($count) {
            for ($i=count($list)-1;$i>=0;$i--) {
                $temp[] = $list[$i];
            }
            $list = $temp;
        }
        return $list;
    }

    protected function executeMigrations($list, $mode='up')
    {
        foreach($list as $migration) {
            echo "\nMigration: ".$migration."\n";
            echo "Initialization...\n";
            $className = '\\quarsintex\\quartronic\\qmigrations\\'.$migration;
            $migrationModel = new $className;
            echo "Start".$mode."...\n";
            $mode=='up' ? $migrationModel->up() : $migrationModel->down();
            echo "\nMigration successfully completed\n";

            if ($mode == 'up') {
                $migrationModel->name = $migration;
                $migrationModel->applied_at = time();
                $migrationModel->save();
            } else {
                $migrationModel = $className::find(['name'=>$migration]);
                $migrationModel->delete();
            }
        }
    }
}

?>