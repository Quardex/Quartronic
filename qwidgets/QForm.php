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
                $className = '\\quarsintex\\quartronic\\qwidgets\\'.$this->fieldTypeToClass($model->structure[$key]['type']);
                return new $className([
                    'key' => $key,
                    'value' => $model->$key,
                    'type' => $model->structure[$key]['type'],
                ]);
            }, $model->fieldList);
        }
    }

    protected function fieldTypeToClass($type)
    {
        switch ($type) {
            case 'text':
                $className = 'QFieldText';
                break;

            default:
                $className = 'QField';
                break;

        }
        return $className;

    }
}

?>