<?php
namespace quarsintex\quartronic\qcore;

class QCrudController extends QController
{
  protected $model;
  protected $crud;

    /**
     * QCrudController constructor.
     * @param $action
     * @param null $model
     */
    function __construct($action, $model = null)
  {
    parent::__construct(ucfirst($action[1]));

    if (defined('static::MODEL')) $this->model = static::MODEL;
    $this->model = $model ? $model : 'Q'.ucfirst($action[0]);
    $this->crud = new \quarsintex\quartronic\qcore\QCrud($this->model);
  }

  function actIndex()
  {
      return self::$Q->render->run('widgets/crud/list', [
          'title' => basename(get_class($this->crud->model)),
          'crud' => $this->crud,
          'countAll' => $this->crud->model->countAll(),
      ]);
  }

  function actView()
  {
      $model = $this->crud->view(self::$Q->request->request);
      if (empty($model->id)) throw new \NotFoundException;

      return self::$Q->render->run('widgets/crud/view', [
          'title' => basename(get_class($this->crud->model)),
          'model' => $model,
      ]);
  }

  function actAdd($modelData = [])
  {
      if (!$modelData) $modelData = self::$Q->request->post;
      if ($modelData) {
          $this->crud->create($modelData);
          $this->redirect(self::$Q->urlManager->route('./'));
      }
      $this->crud->model->scenario = 'create';
      return self::$Q->render->run('widgets/crud/create', [
          'title' => basename(get_class($this->crud->model)),
          'model' => $this->crud->model,
      ]);
  }

  function actEdit()
  {
      if (self::$Q->request->post) {
          $this->crud->update(self::$Q->request->request);
          $this->redirect(self::$Q->urlManager->route('./view',['id'=>$this->crud->model->id]));
      }

      $model = $this->crud->model->find(self::$Q->request->request);
      if (empty($model->id)) throw new \NotFoundException;

      $model->scenario = 'update';
      return self::$Q->render->run('widgets/crud/update', [
          'title' => basename(get_class($this->crud->model)),
          'model' => $model,
      ]);
  }

  function actDelete()
  {
      $this->crud->delete(self::$Q->request->request);
      $backUrl = self::$Q->request->referer;
      if (strpos($backUrl, 'view') !== false) $backUrl = self::$Q->urlManager->route('./index');
      $this->redirect($backUrl);
  }

}

?>