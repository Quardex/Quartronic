<?php
namespace quarsintex\quartronic\qcore;

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

    public function __construct($modelName)
    {
        $this->config = self::loadConfig();
        $ns = self::getNativeStructure();
        $modelName = strtolower($modelName);
        $alias = QModel::getAlias($modelName, 'q');
        if (isset($ns[$alias])) {
            $prefix = 'q';
            $table = $modelName;
        } else {
            $prefix = '';
            $table = $alias;
        }
        if (isset($this->config[$alias]['prefix'])) $prefix = $this->config[$alias]['prefix'];
        $this->model = QModel::initModel($table, $prefix);
        $this->page = intval(self::$Q->request->getParam('page', $this->page));
        $this->pageSize = $this->settings->get('pageSize');
    }

    static function loadConfig()
    {
        $configPath = self::$Q->router->configDir.'qcrud.php';
        $configFromFile = file_exists($configPath) ? include($configPath) : [];
        $configFromDB = [];
        try {
            foreach ((new QModel('qcrud', 'q'))->all as $model) {
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
        $this->model->scenario = 'create';
        if (!$this->model->validate()) return false;
        $this->model->save();
        return true;
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
        $this->model->scenario = 'update';
        if (!$this->model->validate()) return false;
        $this->model->save();
        return true;
    }

    public function delete($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->getByPk($params);
        if ($this->model) $this->model->delete();
    }

    static function getNativeStructure()
    {
        static $cache;
        if (!$cache)
            $cache = [
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
                        'required' => false,
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
                        'passhash' => '$2y$10$9z01egnSwmaxxnH9w10v4O6QtMpTCZ8wi7zs1oiHJODVvJD/Pfmhm',

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
        return $cache;
    }

    static function getAutoStructure()
    {
        static $cache;
        if (!$cache) {
            $cache = array_merge(self::getNativeStructure(), self::loadConfig());
            QModel::$autoStructure = $cache;
        }
        return $cache;
    }

    static function restructDB($verbose = false) {
        $ns = self::getNativeStructure();
        foreach (self::getAutoStructure() as $name => $info) {
            if ($verbose) echo "\n".'Preparing table for crud section "'.$name.'"...';
            if (isset($ns[$name])) {
                $db = self::$Q->sysDB;
                $prefix = 'q';
            } else {
                $db = self::$Q->db;
                $prefix = '';
            }
            if (!empty($info['db'])) $db = self::$Q->{$info['db']};
            $dbBuilder = $db->builder;
            if (isset($info['prefix'])) $prefix = $info['prefix'];
            $tableName = $prefix.$name;
            if (!empty($info['struct'])) {
                if (!$dbBuilder->hasTable($tableName)) {
                    $dbBuilder->create($tableName, function ($table) use ($info) {
                        foreach ($info['struct'] as $fieldName => $fieldInfo) {
                            if (empty($fieldInfo['type'])) $fieldInfo['type'] = $fieldInfo[0];
                            switch ($fieldInfo['type']) {
                                case 'pk':
                                    $filedType = 'increments';
                                    break;

                                case 'dropdown':
                                    $filedType = 'integer';
                                    break;

                                case 'relation':
                                    $filedType = 'unsignedInteger';
                                    $fieldInfo['unique'] = true;
                                    $fieldInfo['required'] = true;
                                    break;

                                default:
                                    $filedType = $fieldInfo['type'];
                                    break;
                            }
                            $field = empty($fieldInfo['length']) ? $table->$filedType($fieldName) : $table->$filedType($fieldName, $fieldInfo['length']);
                            if ($fieldInfo['type'] == 'relation') {
                                if (empty($fieldInfo['onDelete'])) $fieldInfo['onDelete'] = 'CASCADE';
                                if (empty($fieldInfo['onUpdate'])) $fieldInfo['onUpdate'] = 'CASCADE';
                                $table->foreign($fieldName)->references($fieldInfo['target'])->on($fieldInfo['table'])->onDelete($fieldInfo['onDelete'])->onUpdate($fieldInfo['onUpdate']);
                            }

                            unset($fieldInfo[0]);
                            unset($fieldInfo['type']);
                            if (!empty($fieldInfo['required'])) {
                                $fieldInfo['nullable'] = !$fieldInfo['required'];
                                unset($fieldInfo['required']);
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
                            $db->getOrm($tableName)->insert($row);
                        }
                    }
                }
                if ($verbose) echo "\nSuccess!\n";
            } else {
                if ($verbose) echo "\nStructure not found!\n";
            }

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