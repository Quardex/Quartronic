<?php
namespace quarsintex\quartronic\qwidgets;

class QForm extends \quarsintex\quartronic\qcore\QWidget
{
    protected $model;
    public $fields;

    public function __construct($params)
    {
        parent::__construct($params);
        if (isset($params['model'])) {
            $model = $params['model'];
            $this->fields = array_map(function ($key) use ($model) {
                return new \quarsintex\quartronic\qwidgets\QField([
                    'key' => $key,
                    'value' => $model->$key,
                    'type' => $model->structure[$key]['type'],
                ]);
            }, $model->fieldList);
        }
    }
}

?>