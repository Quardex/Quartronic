<?php
namespace quarsintex\quartronic\qcore;

class QExport extends QSource
{
    protected $_components;
    protected $_crudModels;

    protected function getConnectedProperties()
    {
        return [
            'user' => &self::$Q->user,
            'autoStructure' => \quarsintex\quartronic\qcore\QCrud::getAutoStructure(),
        ];
    }

    public function getCrudModel($crudAlias) {
        if (empty($this->_crudModels[$crudAlias])) {
            if (isset($this->autoStructure[$crudAlias])) {
                $config = $this->autoStructure[$crudAlias];
                $modelName = isset($config['modelName']) ? $config['modelName'] : 'Q'.ucfirst($crudAlias);
                $this->_crudModels[$crudAlias] = \quarsintex\quartronic\qcore\QCrud::initModel($modelName);
            } else {
                return null;
            }
        }
        return $this->_crudModels[$crudAlias];
    }

    public function getComponent($name) {
        if (empty($this->_components[$name])) {
            switch($name)
            {
                case 'news':
                    $this->_components[$name] = new \quarsintex\quartronic\qcore\QModel('qnews');
                    break;

                default:
                    return null;
            }
        }
        return $this->_components[$name];
    }

    public function __get($name)
    {
        if (!empty($this->getComponent($name))) {
            return $this->getComponent($name);
        }
        return parent::__get($name);
    }
}