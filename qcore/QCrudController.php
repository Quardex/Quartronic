<?php
namespace quarsintex\quartronic\qcore;

class QCrudController extends QController
    {
    protected $model;
    protected $crud;

    function __construct($action, $model = null)
    {
        parent::__construct(ucfirst($action[1]));

        if (defined('static::MODEL')) $this->model = static::MODEL;
        $this->model = $model ? $model : 'Q'.ucfirst($action[0]);
        $this->crud = new \quarsintex\quartronic\qcore\QCrud($this->model);
    }

    function actIndex()
    {
        try {
            $countAll = $this->crud->model->countAll();
        } catch (\PDOException $e) {
            if (!$this->crud->model->db->builder->hasTable($this->crud->model->getTable())) { //ugly
                \quarsintex\quartronic\qcore\QCrud::restructDB();
                $countAll = $this->crud->model->countAll();
            }
        }
        return self::$Q->render->run('widgets/qcrud/list', [
            'title' => basename(str_replace('\\', '/', get_class($this->crud->model))),
            'crud' => $this->crud,
            'countAll' => $countAll,
        ]);
    }

    function actView()
    {
        $model = $this->crud->read(self::$Q->request->request);
        if (empty($model->id)) throw new \NotFoundException;

        return self::$Q->render->run('widgets/qcrud/view', [
            'title' => basename(str_replace('\\', '/', get_class($this->crud->model))),
            'model' => $model,
        ]);
    }

    function actAdd($modelData = [])
    {
        if (!$modelData) $modelData = self::$Q->request->post;
        if ($modelData && $this->crud->create($modelData)) {
            $this->redirect('./');
        }
        $this->crud->model->scenario = 'create';
        return $this->render->run('widgets/qcrud/create', [
            'title' => basename(str_replace('\\', '/', get_class($this->crud->model))),
            'model' => $this->crud->model,
        ]);
    }

    function actEdit()
    {
        if (self::$Q->request->post && $this->crud->update(self::$Q->request->request)) {
            $this->redirect(self::$Q->urlManager->route('./view', ['id'=>$this->crud->model->id]), true);
        }

        $model = ($this->crud->model->id) ? $this->crud->model : $this->crud->read(self::$Q->request->request);
        if (empty($model->id)) throw new \NotFoundException;

        $model->scenario = 'update';
        return self::$Q->render->run('widgets/qcrud/update', [
            'title' => basename(str_replace('\\', '/', get_class($this->crud->model))),
            'model' => $model,
        ]);
    }

    function actDelete()
    {
        $this->crud->delete(self::$Q->request->request);
        $backUrl = self::$Q->request->referer;
        if (strpos($backUrl, 'view') !== false) $backUrl = self::$Q->urlManager->route('./index');
        $this->redirect($backUrl,true);
    }

    function actSettings()
    {
        if (self::$Q->request->post) {
            foreach (self::$Q->request->post as $key => $value) {
                $this->crud->settings->save($key, $value);
            }
            $this->redirect(self::$Q->urlManager->route('./settings'), true);
        }

        $fields = $this->crud->settings->values;

        array_walk($fields, function(&$value, $key) {
           $value = new \quarsintex\quartronic\qwidgets\QField([
              'key' => $key,
              'title' => $key,
              'value' => $value,
           ]);
        });

        return self::$Q->render->run('widgets/qcrud/settings', [
            'fields' => $fields,
        ]);
    }
}

?>