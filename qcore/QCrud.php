<?php
namespace quarsintex\quartronic\qcore;

use Illuminate\Database\Schema as Schema;

class QCrud extends QSource
{
    protected $model;
    protected $config;

    public $page = 1;
    public $pageSize = 10;

    protected function getConnectedProperties()
    {
        return [
            'db' => self::$Q->db,
            'qRootDir' => &self::$Q->router->qRootDir,
            'configDir' => &self::$Q->router->configDir,
            'settings' => $this->dynUnit(function() {
                return new \quarsintex\quartronic\qcore\QCrudSettings('settings.'.$this->model->table);
            }),
        ];
    }

    static function initModel($modelName)
    {
        $controllerPath = self::$Q->router->qRootDir . 'qmodels/' . $modelName . '.php';
        if (file_exists($controllerPath)) {
            $modelClass = '\\quarsintex\\quartronic\\qmodels\\'.$modelName;
            $model = new $modelClass;
        } else {
            $model = new QModel(strtolower($modelName));
        }
        return $model;
    }

    public function __construct($modelName)
    {
        $this->config = static::loadConfig();
        $this->model = static::initModel($modelName);
        $this->page = intval(self::$Q->request->getParam('page', $this->page));
        $this->pageSize = $this->settings->get('pageSize');
    }

    static function loadConfig()
    {
        $configPath = self::$Q->router->configDir.'qcrud.php';
        $configFromFile = file_exists($configPath) ? include($configPath) : [];
        $configFromDB = [];
        try {
            foreach ((new QModel('qcrud'))->all as $model) {
                $configFromDB[$model->alias] = json_decode($model->config, true);
            }
        } finally {
            return array_merge($configFromFile, $configFromDB);
        }
    }

    public function getOffset()
    {
        return $this->pageSize * ($this->page - 1);
    }

    public function getModelFields()
    {
        return $this->model->getFieldList();
    }

    public function getList()
    {
        $model = $this->model;
        if ($this->pageSize) {
            $model->getAll([
                'limit'=>$this->pageSize,
                'offset'=>$this->offset,
            ]);
        }
        return $model->all;
    }

    public function create($params)
    {
        $this->model->fields = $params;
        $this->model->save();
    }

    public function read($params)
    {
        if (empty($params['id'])) return null;
        return $this->model->getByPk($params);
    }

    public function update($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->getByPk($params);
        $this->model->fields = $params;
        $this->model->save();
    }

    public function delete($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->getByPk($params);
        if ($this->model) $this->model->delete();
    }

    static function getNativeStructure()
    {
        return [
            'user' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'username' => [
                        'string',
                        'length' => '255',
                        'unique' => true,
                    ],
                    'email' => [
                        'string',
                        'null' => true,
                    ],
                    'passhash' => [
                        'string',
                    ],
                ],
                'default' => [
                    [
                        'id' => 1,
                        'username' => 'Quardex',
                        'email' => 'megasounds@mail.ru',
                        'passhash' => '$2y$10$4BjY5DHZuqngI3/JlnRH/egyCqiNy88YBx6cjUCnVaWNxhji1dwA',

                    ],
                    [
                        'id' => 2,
                        'username' => 'Admin',
                        'email' => 'admin@mail.com',
                        'passhash' => '$2y$10$RneSIIYPJL/J5InEStZx9upSe01XFppg9dqhD19H8N.u0NBfq4Si.',
                    ],
                ],
            ],
            'group' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'name' => [
                        'string',
                        'length' => '255',
                        'unique' => true,
                    ],
                ],
            ],
            'role' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'name' => [
                        'string',
                        'length' => '255',
                        'unique' => true,
                    ],
                ],
            ],
            'section' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'name' => [
                        'string',
                        'length' => '255',
                        'unique' => true,
                    ],
                ],
            ],
            'crud' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'alias' => [
                        'string',
                        'length' => '255',
                        'unique' => true,
                    ],
                    'config' => [
                        'text',
                    ],
                ],
            ],
            'storage' => [
                'struct' => [
                    'id' => [
                        'pk',
                    ],
                    'category' => [
                        'string',
                        'length' => '255',
                    ],
                    'key' => [
                        'string',
                        'length' => '255',
                    ],
                    'value' => [
                        'string',
                    ],
                ],
            ],
        ];
    }

    static function getAutoStructure()
    {
        static $cache;
        if (!$cache) {
            $cache = array_merge(self::getNativeStructure(), self::loadConfig());
        }
        return $cache;
    }

    static function restructDB($verbose = false) {
        foreach (self::getAutoStructure() as $name => $info) {
            if ($verbose) echo "\n".'Preparing table for crud section "'.$name.'"...';
            $schema = self::$Q->db->schema;
            $tableName = 'q'.$name;
            if (!empty($info['struct']) && !$schema->hasTable($tableName)) {
                $schema->create($tableName, function ($table) use($info) {
                    foreach ($info['struct'] as $fieldName => $fieldInfo) {
                        $filedType = $fieldInfo[0] == 'pk' ? 'increments' :  $fieldInfo[0];
                        $field = empty($fieldInfo['length']) ? $table->$filedType($fieldName) : $table->$filedType($fieldName, $fieldInfo['length']);
                        unset($fieldInfo[0]);
                        if (!empty($fieldInfo['null'])) {
                            $fieldInfo['nullable'] = $fieldInfo['null'];
                            unset($fieldInfo['null']);
                        }

                        foreach ($fieldInfo as $key => $value) {
                            $field->$key($value);
                        }
                    }
                    $table->timestamp('created_at')->useCurrent();
                    $table->timestamp('updated_at')->useCurrent();
                });
                if (!empty($info['default'])) {
                    foreach ($info['default'] as $row) {
                        self::$Q->db->getOrm($tableName)->insert($row);
                    }
                }
            }
            if ($verbose) echo "\nSuccess!\n";
        }
    }

    public function isIgnoredFields($name) {
        $ignoredFields = [
            'created_at',
            'updated_at',
        ];
        $ignoredFields = array_flip($ignoredFields);
        return isset($ignoredFields[$name]);
    }
}

?>