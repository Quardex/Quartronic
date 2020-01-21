<?php
namespace quarsintex\quartronic\qcore;

class QCrud extends QSource
{
  protected $model;
  public $page = 1;
  public $limit = 10;

  function __construct($model)
  {
      $modelClass = '\\quarsintex\\quartronic\\qmodels\\'.$model;
      $this->model = new $modelClass;
      $this->page = intval(self::$Q->request->getParam('page', $this->page));
  }

  function getOffset() {
      return $this->limit * ($this->page - 1);
  }

  function getModelFields() {
      return $this->model->getFieldList();
  }

  function getList() {
      $model = $this->model;
      if ($this->limit) {
          $model->query->limit($this->limit)->offset($this->offset);
      }
      return $model->all;
  }

  function create()
  {

  }

  function read()
  {

  }

  function update()
  {

  }

  function delete($params)
  {
      var_dump($params);
  }

  static function createTable() {
  }
}

?>