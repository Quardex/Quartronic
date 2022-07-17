<?php
namespace quarsintex\quartronic\qcore;

class QModel extends QSource
{
    protected $db;
    protected $prefix;
    public $scenario;

    protected $_table;
    protected $_alias;
    protected $_fields = [];
    protected $_relations = [];
    protected $_structure = [];
    protected $_rules = [];
    protected $_fieldList = [];
    protected $_titleList = [];
    protected $_primaryKeys = [];
    protected $_query;
    protected $new = true;
    protected $errors = [];

    static $nativeStructure;
    static $autoStructure;

    protected function getDefaultConnectedProperties()
    {
        return [
        ];
    }

    static function getAlias($modelName, $prefix = '')
    {
        return preg_replace('/^'.$prefix.'/', '', $modelName);
    }

    static protected function getDefaultDB($modelName, $prefix = '')
    {
        if ($modelName == 'qcrud') return self::$Q->sysDB;
        $alias = self::getAlias($modelName, $prefix);
        if (isset(self::$nativeStructure[$alias])) {
            $db = 'sysDB';
        } else {
            $db = 'db';
        }
        if (!empty(self::$autoStructure[$alias]['db'])) $db = self::$autoStructure[$alias]['db'];
        return self::$Q->$db;
    }

    function __construct($table = null, $prefix = '', $db = null)
    {
        if (!self::$nativeStructure) {
            self::$nativeStructure = QCrud::getNativeStructure();
        }
        if (!self::$autoStructure && $table != 'qcrud') {
            self::$autoStructure = QCrud::getAutoStructure();
        }

        if (defined('static::TABLE')) $this->_table = static::TABLE;
        if ($table) $this->_table = $table;
        if (!$this->_table) $this->_table = mb_strtolower(basename(static::class));

        if (!$this->prefix) $this->prefix = $prefix;
        if (!$this->prefix && isset(self::$nativeStructure[self::getAlias($this->_table, 'q')])) $this->prefix = 'q';

        $this->_alias = self::getAlias($this->_table, $this->prefix);

        $this->_connectedProperties = array_merge($this->getDefaultConnectedProperties(), $this->getConnectedProperties());

        $this->db = $db ? $db : self::getDefaultDB($this->_table, $prefix);

        $this->loadRules();
        $this->loadStructure();
        $this->loadRelations();
    }

    function getDb()
    {
        return $this->db;
    }

    function getTable()
    {
        return $this->_table;
    }

    function getPrimaryKey()
    {
        $pks = [];
        foreach ($this->_primaryKeys as $field) {
            $pks[$field] = $this->$field;
        }
        return $pks;
    }

    function getPrimaryKeys2SqlString()
    {
        $pkv = $this->primaryKey;
        $str = [];
        foreach ($pkv as $name => $value) {
            $str[] = $name . ' = "' . $value . '"';
        }
        return implode(' AND ', $str);
    }

    static function initModel($modelName, $prefix = '')
    {
        $controllerPath = self::$Q->router->qRootDir . '../../../' . self::$Q->appDir . 'qmodels/' . $modelName . '.php';
        if (file_exists($controllerPath)) {
            $modelClass = basename(self::$Q->appDir) . '\\qmodels\\'.$modelName;
            $model = new $modelClass;
        } else {
            $controllerPath = self::$Q->router->qRootDir . 'qmodels/' . $modelName . '.php';
            if (file_exists($controllerPath)) {
                $modelClass = '\\quarsintex\\quartronic\\qmodels\\'.$modelName;
                $model = new $modelClass(null, $prefix);
            } else {
                $model = new QModel(strtolower($modelName), $prefix);
            }
        }
        return $model;
    }

    protected function loadStructure()
    {
        $tableInfo = $this->db->connection->getDoctrineSchemaManager()->listTableDetails($this->table);
        $fields = $tableInfo->getColumns();
        $indexes = $tableInfo->getIndexes();
        foreach ($fields as $field => $fieldInfo) {
            $uniqueIndexName = $this->table.'_'.$field.'_unique';
            $this->_structure[$field] = [
                'type' => $fieldInfo->getType()->getName(),
                'default' => $fieldInfo->getDefault(),
                'required' => $fieldInfo->getNotNull() && $fieldInfo->getDefault() === null,
                'unique' => !empty($indexes[$uniqueIndexName]) ? $indexes[$uniqueIndexName]->isUnique() : false,
                'length' => $fieldInfo->getLength(),
                'autoincrement' => $fieldInfo->getAutoincrement(),
            ];
        }
        $this->_primaryKeys[] = 'id';
        if (!empty($indexes['primary'])) $this->_primaryKeys = $indexes['primary']->getColumns();
        if (count($this->_primaryKeys) == 1) $this->_structure[$this->_primaryKeys[0]]['unique'] = true;
        $alias = $this->_alias;
        if (self::$autoStructure && isset(self::$autoStructure[$alias]['struct'])) {
            if (!is_array(self::$autoStructure[$alias]['struct'])) self::$autoStructure[$alias]['struct'] = [self::$autoStructure[$alias]['struct']];
            foreach (self::$autoStructure[$alias]['struct'] as $fieldName => $field) {
                $this->_structure[$fieldName]['type'] = $field[!empty($field['type']) ? 'type' : 0];
                unset($field[0]);

                foreach ($field as $key => $value) {
                    $this->_structure[$fieldName][$key] = $value;
                }
            }
        }
    }

    protected function loadRelations()
    {
        foreach ($this->_structure as $key => $field) {
            if ($field['type'] == 'relation') {
                $keyWithoutID = preg_replace('/_id$/', '', $key);
                if (!empty($this->_fields[$key]) && $keyWithoutID != $key) {
                    $value = $this->_fields[$key];
                    $this->_relations[$keyWithoutID] = new QRelation(function () use ($field, $value) {
                        $prefix = !empty($field['prefix']) ? $field['prefix'] : '';
                        return QModel::initModel($field['table'], $prefix)->getByPk($value);
                    }, $field['target'], $field['titleField']);
                }
            }
        }
    }

    protected function loadRules()
    {
        foreach ($this->rules as $rule) {
            switch ($rule[1]) {
                case 'required':
                    $value = true;
                    break;
            }
            foreach (explode(',', $rule[0]) as $name) {

                $this->_structure[$name][$rule[0]] = $value;
            };
        }
    }

    protected function getRules()
    {
        return [];
    }

    protected function isRequiredField($field, $isRequired = false)
    {
        return isset($this->_structure[$field]['required']) ? $this->_structure[$field]['required'] : $isRequired;
    }

    function validate() {
        $pks = $this->getPrimaryKey();
        foreach ($this->_structure as $field => $attrs) {
            if ($this->isRequiredField($field) && (
                    array_key_exists($field, $pks) && $this->$field === '' ||
                    !array_key_exists($field, $pks) && strlen($this->$field) == 0
                )
            ) {
                $this->errors[$field]['message'] = 'This field is required';
            } elseif ($this->$field && $attrs['unique'] && $this->getOne([['where', [$field=>$this->$field]], ['where', 'id', '!=', $this->id]])) {
                $this->errors[$field]['message'] = 'This field must be unique';
            }
        }
        return !$this->errors;
    }

    function getStructure()
    {
        return $this->_structure;
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->_structure)) {
            if (!array_key_exists($name, $this->_fields)) $this->_fields[$name] = null;
            return $this->_fields[$name];
        } elseif (array_key_exists($name, $this->_relations)) {
            return $this->_relations[$name];
        }
        return parent::__get($name);
    }

    function __set($name, $value)
    {
        if (!empty($this->_structure[$name]['type']) && $this->_structure[$name]['type'] == 'relation' && $value === '') $value = null;
        array_key_exists($name, $this->_structure) ?
            $this->_fields[$name] = $value :
            parent::__set($name, $value);
    }

    function __isset($name)
    {
        if (isset($this->_structure[$name]) || isset($this->_relations[$name])) {
            return true;
        }
        return parent::__isset($name);
    }

    function getFieldList($ignoreRelation = false)
    {
        if (!$this->_fieldList) $this->_fieldList = array_keys($this->_structure);
        if ($ignoreRelation) {
            $tempFieldList = $this->_fieldList;
            foreach ($tempFieldList as $key => $value) {
                if (is_object($value)) unset($tempFieldList[$key]);
            }
            return $tempFieldList;
        }
        return $this->_fieldList;
    }

    function getTitleList($ignoreRelation = false)
    {
        if (!$this->_titleList) {
            $this->_titleList = array_flip($this->getFieldList($ignoreRelation));
            foreach ($this->_titleList as $field => &$title) {
                $title = $this->_structure[$field]['title'] ?? $field;     
                if ($this->_structure[$field]['type'] == 'relation') $title = preg_replace('/_id$/', '', $title); 
            }
        }
        return $this->_titleList;
    }

    function getFields($ignoreRelation = false)
    {
        if ($ignoreRelation) {
            $tempFields = $this->_fields;
            foreach ($tempFields as $key => $value) {
                if (is_object($value)) unset($tempFields[$key]);
            }
            return $tempFields;
        }
        return $this->_fields;
    }

    function setFields($fields)
    {
        foreach ($fields as $name => $value) {
            if (array_key_exists($name, $this->_structure)) $this->$name = $value;
        }
        $this->loadRelations();
    }

    protected function insert()
    {
        foreach ($this->_primaryKeys as $fieldName) {
            if (array_key_exists($fieldName, $this->_fields) && !$this->_fields[$fieldName]) unset($this->_fields[$fieldName]);
        }
        $this->db->insert($this);
    }

    protected function update()
    {
        if (isset($this->updated_at)) $this->updated_at = date('Y-m-d H:i:s');
        $this->db->update($this);
    }

    function delete()
    {
        $this->db->delete($this);
    }

    function save()
    {
        $this->new ? $this->insert() : $this->update();
    }

    function prepareModels($allRows)
    {
        $models = [];
        foreach ($allRows as $row) {
            $model = new static($this->getTable(), $this->prefix);
            $model->fields = $row;
            $model->new = false;
            $models[] = $model;
        }
        return $models;
    }

    function getOne($params='')
    {
        $row = $this->db->findOne($this, $params);
        if ($row) {
            $model = new static($this->getTable(), $this->prefix);
            $model->fields = $row;
            $model->new = false;
            return $model;
        }
        return null;
    }

    static function findOne($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->getOne($where);
    }

    function getByPk($where='')
    {
        $pkWhere = $where;
        if (is_array($where)) {
            $pkWhere = [];
            $pks = $this->_primaryKeys;
            foreach ($pks as $fieldName) {
                if (array_key_exists($fieldName, $where)) $pkWhere[$fieldName] = $where[$fieldName];
            }
        } elseif (ctype_digit((string)$pkWhere)) $pkWhere = [$this->_primaryKeys[0] => $pkWhere];
        return $this->getOne(['where'=>$pkWhere]);
    }

    static function findByPk($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->getByPk($where);
    }

    function getAll($params='')
    {
        $result = $this->prepareModels($this->db->find($this, $params));
        return $result;
    }

    static function findAll($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->getAll($where);
    }

    function countAll()
    {
        return $this->db->countAll($this);
    }
}

?>