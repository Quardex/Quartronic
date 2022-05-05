<?php
namespace quarsintex\quartronic\qwidgets;

use quarsintex\quartronic\qcore\QModel;

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
                $initParams = $this->getInitParamsByFieldType($model->structure[$key]);
                if (!is_array($initParams)) $initParams = [$initParams];
                $className = '\\quarsintex\\quartronic\\qwidgets\\'.$initParams[0];
                unset($initParams[0]);
                if (isset($model->errors[$key])) $initParams['error'] = $model->errors[$key]['message'];
                return new $className(array_merge([
                    'key' => $key,
                    'value' => (string)$model->$key,
                    'type' => $model->structure[$key]['type'],
                    'required' => $model->structure[$key]['required'],
                    'unique' => $model->structure[$key]['unique'],
                    'autoincrement' => $model->structure[$key]['autoincrement'],
                    'default' => $model->structure[$key]['default'],
                    'length' => $model->structure[$key]['length'],
                ], $initParams));
            }, $model->fieldList);
        }
    }

    protected function getInitParamsByFieldType($field)
    {
        switch ($field['type']) {
            case 'text':
                $initParams = ['QFieldWysiwyg',
                    'editor'=>'TinyMCE',
                ];
                break;

            case 'textarea':
                $initParams = 'QFieldText';
                break;

            case 'relation':
                if (empty($field['titleField'])) {
                    throw new \Exception('Attribute "titleField" can\'t be empty');
                }
                if (empty($field['target'])) {
                    throw new \Exception('Attribute "target" can\'t be empty');
                }
                $prefix = !empty($field['prefix']) ? $field['prefix'] : '';
                foreach (QModel::initModel($field['table'], $prefix)->getAll() as $model) {
                    $initParams['options'][$model->{$field['target']}] = $model->{$field['titleField']};
                }
            case 'dropdown':
                $initParams[0] = 'QDropdown';
                if (isset($field['options'])) $initParams['options'] = $field['options'];
                break;

            default:
                $initParams = 'QField';
                break;

        }
        return $initParams;

    }
}

?>