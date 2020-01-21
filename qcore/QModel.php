<?php
namespace quarsintex\quartronic\qcore;

class QModel extends QSource
{
  protected $_fields = [];
  protected $_structure = [];
  protected $_fieldList = [];
  protected $_primaryKeys = [];
  protected $_query = [];
  protected $new = true;

  function __construct()
  {
     $this->loadStructure();
  }

  function getTable()
  {
      return static::TABLE;
  }

  function getPrimaryKey() {
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
      $fields = self::$Q->db->query("PRAGMA table_info(".$this->table.");");
      foreach ($fields as $field) {
        $this->_structure[$field['name']] = $field['type'];
        if ($field['pk']) $this->_primaryKeys[] = $field['name'];
      }
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

  static function query($mode='from')
  {
      return self::$Q->db->$mode(static::TABLE);
  }

  function getQuery($mode='from') {
      if (empty($this->_query[$mode])) {
          $this->_query[$mode] = self::query($mode);
      }
      return $this->_query[$mode];
  }

  protected function insert()
  {
    self::query('insertInto')->values($this->_fields)->execute();
  }

  protected function update()
  {
    self::query('update')->set($this->_fields)->where($this->primaryKeys2SqlString)->execute();
  }

  function delete()
  {
    self::query('deleteFrom')->where($this->primaryKeys2SqlString)->execute();
  }

  function save()
  {
    $this->new ? $this->insert() : $this->update();
  }

  static function find($where='')
  {
      $row = self::query()->where($where)->fetch();
      if ($row) {
          $model = new static();
          $model->fields = $row;
          $model->new = false;
          return $model;
      }
      return null;
  }

  static function prepareModels($allRows) {
      $models = [];
      foreach ($allRows as $row) {
          $model = new static();
          $model->fields = $row;
          $model->new = false;
          $models[] = $model;
      }
      return $models;
  }

  static function findAll($where='')
  {
      return self::prepareModels(self::query()->where($where)->fetchAll());
  }

  function getAll($where='') {
      return self::prepareModels($this->query->where($where)->fetchAll());
  }

  static function countAll() {
      $result = self::query()->select(null)->select('count(*)')->fetch();
      return $result['count(*)'];
  }

}

?>