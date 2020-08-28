<?php
namespace quarsintex\quartronic\qcore;

use Illuminate\Database\Capsule\Manager as Capsule;

class QDbDriver extends QSource
{
    protected $capsula;
    protected $schema;

    protected function getConnectedProperties()
    {
        return [
            'dbDir' => &self::$Q->params['runtimeDir'],
        ];
    }

    protected function getDefaultParams() {
        return [
            'driver'    => 'sqlite',
            'database'  => $this->dbDir.'q.db',
        ];
    }

    public function __construct($params=[])
    {
        $this->capsula = new Capsule;

        $config = array_merge($this->getDefaultParams(), $params);

        $this->capsula->addConnection($config);
        //$capsule->setAsGlobal();
        $this->capsula->bootEloquent();
        $this->schema = $this->capsula->getConnection()->getSchemaBuilder();
    }

    public function getOrm($table) {
        return $this->capsula->getConnection()->table($table);
    }

    public function find($model, $params=[]) {
        $orm = $this->getOrm($model->table);

        if (!is_array($params)) $params['where'] = $params;
        if (isset($params[0]) && is_string($params[0])) $params = [$params];
        foreach ($params as $param => $value) {
            if (is_int($param)) {
                $method = $value[0];
                unset($value[0]);
                call_user_func_array(array($orm, $method), $value);
            } else {
                $orm->$param($value);
            }
        }
        return $orm->get();
    }

    public function findOne($model, $params=[]) {
        return $this->find($model, $params)->first();
    }

    public function countAll($model) {
        return $this->getOrm($model->table)->count();
    }

    public function insert($model) {
        $fields = $model->getFields();
        unset($fields['created_at']);
        unset($fields['updated_at']);
        $this->getOrm($model->table)->insert($fields);
    }

    public function update($model) {
        $this->getOrm($model->table)->where($model->getPrimaryKey())->update($model->getFields());
    }

    public function delete($model) {
        $this->getOrm($model->table)->delete($model->id);
    }
}

?>