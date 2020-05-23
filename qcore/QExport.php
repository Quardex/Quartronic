<?php
namespace quarsintex\quartronic\qcore;

class QExport extends QSource
{
    protected $_components;

    protected function getConnectedProperties()
    {
        return [
            'user' => &self::$Q->user,
            'autoStructure' => \quarsintex\quartronic\qcore\QCrud::getAutoStructure(),
        ];
    }

    public function getComponent($name)
    {
        if (empty($this->_components['models'][$name])) {
            if (isset($this->autoStructure[$name])) {
                try {
                    $config = json_decode($this->autoStructure[$name], true);
                } finally {
                    $modelName = isset($config['modelName']) ? $config['modelName'] : 'Q'.ucfirst($name);
                }
                $this->_components['models'][$name] = \quarsintex\quartronic\qcore\QCrud::initModel($modelName);
            } else {
                return null;
            }

        }
        return $this->_components['models'][$name];
    }

    public function __get($name)
    {
        if (!empty($this->getComponent($name))) {
            return $this->getComponent($name);
        }
        return parent::__get($name);
    }
}