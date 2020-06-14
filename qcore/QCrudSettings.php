<?php
namespace quarsintex\quartronic\qcore;

class QCrudSettings extends QStorage
{
    public function getDefaultSettings() {
        return [
            'pageSize' => 10,
        ];
    }

    public function __construct($category)
    {
        parent::__construct($category);

        foreach (array_reverse($this->getDefaultSettings()) as $key => $value) {
            if (!isset($this->_values[$key])) {
                $this->save($key, $value, true);
                $this->_values = array_merge([$key=>$value], $this->_values);
            }
        }
    }

}


?>