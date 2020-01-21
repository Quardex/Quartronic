<?php
namespace quarsintex\quartronic\qcore;

class QCrudController extends QController
{
  protected $model;
  protected $crud;

  function __construct($action, $model = null)
  {
    parent::__construct($action);
    $this->model = $model ? $model : static::MODEL;
    $this->crud = new \quarsintex\quartronic\qcore\QCrud($this->model);
  }

  function actIndex()
  {
      return self::$Q->render->run('widgets/crud/list', [
          'title' => basename(get_class($this->crud->model)),
          'crud' => $this->crud,
          'countAll' => get_class($this->crud->model)::countAll(),
      ]);
  }

  function actView()
  {
      echo 123;
  }

  function actEdit()
  {

  }

  function actDelete()
  {
      $this->crud->delete(self::$Q->request->request);
      $this->redirect(self::$Q->request->referer);
  }

}

?>