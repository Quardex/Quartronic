<?php
namespace quarsintex\quartronic\qcore;

class QModel extends QSource
{
    public $scenario = '';

    protected $_table;
    protected $_fields = [];
    protected $_structure = [];
    protected $_rules = [];
    protected $_fieldList = [];
    protected $_primaryKeys = [];
    protected $_query = [];
    protected $new = true;
    protected $errors = [];

    function __construct($table = null)
    {
        if (defined('static::TABLE')) $this->_table = static::TABLE;
        if ($table) $this->_table = $table;
        $this->loadRules();
        $this->loadStructure();
    }

    function getTable()
    {
        return $this->_table;
    }

    function getPrimaryKey()
    {
        $pk = [];
        $primaryKeys = $this->_primaryKeys ? $this->_primaryKeys : $this->fieldList;
        foreach ($primaryKeys as $name) {
          $pk[$name] = $this->_fields[$name];
        }
        return $pk;
    }

    function getPrimaryKeys2SqlString()
    {
        $pkv = $this->primaryKey;
        $str = [];
        foreach ($pkv as $name => $value) {
            $str[] = $name.' = "'.$value.'"';
        }
        return implode(' AND ', $str);
    }

    protected function loadStructure()
    {
        $fields = self::$Q->db->query("PRAGMA table_info(`".$this->table."`);");
        foreach ($fields as $field) {
            $this->_structure[$field['name']] = [
                'type' => $field['type'],
                'not_null' => $field['notnull'],
                'default' => $field['dflt_value'],
                'required' => $this->isRequiredField($field['name'], $field['notnull'])
            ];
            if ($field['pk']) $this->_primaryKeys[] = $field['name'];
        }
    }

    protected function loadRules()
    {
        foreach ($this->rules as $rule) {
            foreach (explode(',', $rule[0]) as $name) {
                $fileds[$name] = $name;
            };
            $this->_rules[$rule[1]] = $fileds;
        }
    }

    protected function getRules()
    {
        return [];
    }

    protected function isRequiredField($field, $isRequired)
    {
        return isset($this->_rules['required']) && isset($this->_rules['required'][$field]) ? $this->_rules['required'][$field] : $isRequired;
    }

    function getStructure()
    {
        return $this->_structure;
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->_structure)) {
            return $this->_fields[$name];
        }
        return parent::__get($name);
    }

    function __set($name, $value)
    {
        array_key_exists($name, $this->_structure) ?
            $this->_fields[$name] = $value :
            parent::__set($name, $value);
    }

    function __isset($name)
    {
        if (isset($this->_structure[$name])) {
            return true;
        }
        return parent::__isset($name);
    }

    function getFieldList()
    {
        if (!$this->_fieldList) $this->_fieldList = array_keys($this->_structure);
        return $this->_fieldList;
    }

    function getFields()
    {
        return $this->_fields;
    }

    function setFields($fields)
    {
        foreach ($fields as $name => $value) {
            if (array_key_exists($name, $this->_structure)) $this->$name = $value;
        }
    }

    function query($mode='from')
    {
        return self::$Q->db->$mode($this->getTable());
    }

    function getQuery($mode='from')
    {
        return $this->query($mode);
    }

    protected function insert()
    {
        foreach ($this->_primaryKeys as $fieldName) {
            if (array_key_exists($fieldName, $this->_fields) && !$this->_fields[$fieldName]) unset($this->_fields[$fieldName]);
        }
        $this->query('insertInto')->values($this->_fields)->execute();
    }

    protected function update()
    {
        $this->query('update')->set($this->_fields)->where($this->primaryKeys2SqlString)->execute();
    }

    function delete()
    {
        $this->query('deleteFrom')->where($this->primaryKeys2SqlString)->execute();
    }

    function save()
    {
        $this->new ? $this->insert() : $this->update();
    }

    static function find($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->search($where);
    }

    function search($where='')
    {
        $row = $this->query()->where($where)->fetch();
        if ($row) {
            $model = new static($this->getTable());
            $model->fields = $row;
            $model->new = false;
            return $model;
        }
        return null;
    }

    static function findByPk($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->searchByPk($where);
    }

    function searchByPk($where='')
    {
        $pkWhere = $where;
        if (is_array($where)) {
            $pkWhere = [];
            $pks = $this->_primaryKeys;
            foreach ($pks as $fieldName) {
                if (array_key_exists($fieldName, $where)) $pkWhere[$fieldName] = $where[$fieldName];
            }
        }
        return $this->search($pkWhere);
    }

    function prepareModels($allRows)
    {
        $models = [];
        foreach ($allRows as $row) {
            $model = new static($this->getTable());
            $model->fields = $row;
            $model->new = false;
            $models[] = $model;
        }
        return $models;
    }

    static function findAll($where='')
    {
        if (self::class == static::class) throw new \Exception('This method must be called from the inheritors of the class');
        return (new static)->getAll($where);
    }

    function getAll($where='')
    {
        return $this->prepareModels(static::query()->where($where)->fetchAll());
    }

    function countAll()
    {
        $query = isset($this) ? $this->query : static::query();
        $result = $query->select(null)->select('count(*)')->fetch();
        return $result['count(*)'];
    }
}

?>