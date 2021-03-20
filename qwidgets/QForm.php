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
                $initParams = $this->getInitParamsByFieldType($model->structure[$key]['type']);
                if (!is_array($initParams)) $initParams = [$initParams];
                $className = '\\quarsintex\\quartronic\\qwidgets\\'.$initParams[0];
                unset($initParams[0]);
                if (isset($model->errors[$key])) $initParams['error'] = $model->errors[$key]['message'];
                return new $className(array_merge([
                    'key' => $key,
                    'value' => $model->$key,
                    'type' => $model->structure[$key]['type'],
                ], $initParams));
            }, $model->fieldList);
        }
    }

    protected function getInitParamsByFieldType($type)
    {
        switch ($type) {
            case 'text':
                $className = ['QFieldWysiwyg',
                    'editor'=>'TinyMCE',
                ];
                break;

            case 'textarea':
                $className = 'qFieldText';
                break;

            case 'dropdown':
                $className = 'Q'.ucfirst($type);
                break;

            default:
                $className = 'QField';
                break;

        }
        return $className;

    }
}

?>