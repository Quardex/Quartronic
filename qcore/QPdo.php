<?php
namespace quarsintex\quartronic\qcore;

use PDO;
use Envms\FluentPDO\Structure;

class QPdo extends \Envms\FluentPDO\Query
{
    protected function getConnectedProperties()
    {
        return [
            'dbDir' => &self::$Q->params['runtimeDir'],
        ];
    }

    public function __construct($pdo=null, Structure $structure = null)
    {
        if (!is_object($pdo)) {
            $parts = explode(':', $pdo);
            $dbType = array_shift($parts);
            $isSysDB = $dbType == 'sqlite' && array_pop($parts) == 'sys';
            $dbFile = preg_replace('/^'.$dbType.':|:sys$/', '', $pdo);
            $dbExist = $isSysDB ? file_exists($dbFile) : true;;
            $pdo = new \PDO($dbType.':'.$dbFile,null,null,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ));
            if (!$dbExist) \quarsintex\quartronic\qcore\QCrud::restructDB();
        }
        parent::__construct($pdo, $structure);
    }

    public function exec($sql)
    {
        try {
            $this->getPDO()->exec($sql);
        } catch(\PDOException $e) {
            echo "DB Error:\n".$e->getMessage();
            exit;
        }
    }

    public function query($sql)
    {
        try {
            return $this->getPDO()->query($sql)->fetchAll();
        } catch(\PDOException $e) {
            echo "DB Error:\n".$e->getMessage();
            exit;
        }
    }
}

?>