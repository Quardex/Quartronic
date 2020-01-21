<?php
namespace quarsintex\quartronic\qcore;

class QPdo extends \Envms\FluentPDO\Query
{
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