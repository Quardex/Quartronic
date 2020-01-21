<?php
namespace quarsintex\quartronic\qmodels;

class QMigration extends \quarsintex\quartronic\qcore\QModel
{
    const TABLE = 'qmigration';

    protected function loadStructure()
    {
        parent::loadStructure();
        if (!$this->_structure) {
            $this->_structure['name'] = 'TEXT';
            $this->_structure['apply_time'] = 'TEXT';
        }
    }
}

?>