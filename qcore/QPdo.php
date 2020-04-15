<?php

namespace quarsintex\quartronic\qcore;

use PDO;
use Envms\FluentPDO\Structure;

class QPdo extends \Envms\FluentPDO\Query
{
    protected $_dbList;

    function __construct(\PDO $pdo=null, Structure $structure = null)
    {
        if (!$pdo) $pdo = new \PDO('sqlite:' . __DIR__.'/../q.db',null,null,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ));
        parent::__construct($pdo, $structure);
    }

    function exec($sql)
    {
        try {
            $this->getPDO()->exec($sql);
        } catch(\PDOException $e) {
            echo "DB Error:\n".$e->getMessage();
            exit;
        }
    }

    function query($sql)
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