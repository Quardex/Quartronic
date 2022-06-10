<?php
namespace quarsintex\quartronic\qcore;

class QExport extends QSource
{
    protected $_components;
    protected $_models;

    protected function getConnectedProperties()
    {
        return [
            'user' => self::$Q->user,
            'autoStructure' => \quarsintex\quartronic\qcore\QCrud::getAutoStructure(),
        ];
    }

    public function getModel($alias) {
        if (empty($this->_models[$alias])) {
            if (isset($this->autoStructure[$alias])) {
                $config = $this->autoStructure[$alias];
                $modelName = isset($config['modelName']) ? $config['modelName'] : 'Q'.ucfirst($alias);
                $this->_models[$alias] = \quarsintex\quartronic\qcore\QModel::initModel($modelName);
            } else {
                return null;
            }
        }
        return $this->_models[$alias];
    }

    public function getComponent($name) {
        if (empty($this->_components[$name])) {
            switch($name)
            {
                case 'news':
                    $this->_components[$name] = new \quarsintex\quartronic\qcore\QModel('qnews', 'q');
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