<?php
namespace quarsintex\quartronic\qcore;

class QModel extends QSource
{
    private $db;
    public $scenario = '';

    protected $_table;
    protected $_fields = [];
    protected $_structure = [];
    protected $_rules = [];
    protected $_fieldList = [];
    protected $_primaryKeys = [];
    protected $_query;
    protected $new = true;
    protected $errors = [];

    static $autoStructure;

    protected function getDefaultConnectedProperties()
    {
        return [
            'sysDB' => self::$Q->sysDB,
            'curDB' => self::$Q->db,
            'nativeStructure' => \quarsintex\quartronic\qcore\QCrud::getNativeStructure(),
        ];
    }

    protected function getAlias()
    {
        return preg_replace('/^q/', '', $this->_table);
    }

    function isNative()
    {
        static $cache;
        if (!$cache) {
            $cache = $this->nativeStructure;
        }
        return isset($cache[$this->getAlias()]);
    }

    protected function getDefaultDB()
    {
        return $this->isNative() ? $this->sysDB : $this->curDB;
    }

    function __construct($table = null, $db = null)
    {
        if (defined('static::TABLE')) $this->_table = static::TABLE;
        if ($table) $this->_table = $table;
        if (!$this->_table) $this->_table = mb_strtolower(basename(static::class));

        $this->_connectedProperties = array_merge($this->getDefaultConnectedProperties(), $this->getConnectedProperties());

        $this->db = $db ? $db : $this->getDefaultDB();

        $this->loadRules();
        $this->loadStructure();
    }

    function getDb() {
        return $this->db;
    }

    function getTable()
    {
        return $this->_table;
    }

    function getPrimaryKey()
    {
        return ['id'=>$this->id];
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
        $fields = $this->db->schema->getColumnListing($this->table);
        foreach ($fields as $field) {
            $this->_structure[$field] = [
                'type' => $this->db->schema->getColumnType($this->table, $field),
                'default' => '',
                'required' => false,
                'unique' => false,
            ];
        }
        $this->_primaryKeys[] = 'id';
        if (self::$autoStructure && isset(self::$autoStructure[$this->getAlias()]['struct'])) {
            foreach (self::$autoStructure[$this->getAlias()]['struct'] as $alias => $field) {
                $this->_structure[$alias] = [
                    'type' => $field[0],
                    'default' => isset($field['default']) ? : '',
                    'required' => isset($field['required']) ? : true,
                    'unique' => isset($field['unique']) ? : false,
                ];
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
        foreach ($this->_structure as $field => $attrs) {
            if ($this->isRequiredField($field) && $this->$field==='') $this->errors[$field]['message'] = 'This field is required';
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

    protected function insert()
    {
        foreach ($this->_primaryKeys as $fieldName) {
            if (array_key_exists($fieldName, $this->_fields) && !$this->_fields[$fieldName]) unset($this->_fields[$fieldName]);
        }
        $this->db->insert($this);
    }

    protected function update()
    {
        $this->db->update($this);;
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
            $model = new static($this->getTable());
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
            $model = new static($this->getTable());
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
        }
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