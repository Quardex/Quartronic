<?php
namespace quarsintex\quartronic\qcore;

class QStorage extends QSource
{
    protected $category;
    protected $_values = [];

    public function __construct($category)
    {
        $this->category = $category;
        $model = new QModel('qstorage');
        $list = $model->query->where(['category'=>$this->category])->fetchAll();
        foreach ($list as $row) {
            $this->_values[$row['key']] = $row['value'];
        }
    }

    public function reload($key)
    {
        //TODO
    }

    public function getValues()
    {
        return $this->_values;
    }

    public function get($key)
    {
        return $this->_values[$key];
    }

    public function save($key, $value, $createEvenNotExist=false)
    {
        $model = new QModel('qstorage');
        $model = $model->search(['where', 'category'=>$this->category, 'key' => $key]);
        if (!$model && $createEvenNotExist) {
            $model = new QModel('qstorage');
            $model->category = $this->category;
            $model->key = $key;
        }
        $model->value = $value;
        $model->save();
    }

}


?>