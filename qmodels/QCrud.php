<?php
namespace quarsintex\quartronic\qmodels;

class QCrud extends \quarsintex\quartronic\qcore\QModel
{
    protected function loadStructure()
    {
        parent::loadStructure();
        $this->_structure['config']['type'] = 'textarea';
    }
}

?>