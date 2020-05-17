<?php
namespace quarsintex\quartronic\qcore;

class QExport extends QSource
{
    protected $_components;

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

    protected function getConnectedProperties()
    {
        return [
            'user' => &self::$Q->user,
        ];
    }

    public function __get($name)
    {
        if (!empty($this->getComponent($name))) {
            return $this->getComponent($name);
        }
        return parent::__get($name);
    }
}