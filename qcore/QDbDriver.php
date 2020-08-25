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
//            'host'      => 'localhost',
//            'username'  => 'root',
//            'password'  => '',
//            'charset'   => 'utf8',
//            'collation' => 'utf8_unicode_ci',
//            'prefix'    => '',
        ];
    }

    public function __construct($params=[])
    {
        $this->capsula = $capsule = new Capsule;

        $config = array_merge($this->getDefaultParams(), $params);

        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->schema = $this->capsula::getSchemaBuilder();
    }

    public function getOrm($table) {
        return $this->capsula::table($table);
    }

    public function find($model, $params=[]) {
        $orm = $this->getOrm($model->table);
        if (!is_array($params)) $params['where'] = $params;
        foreach ($params as $param => $value) {
            if ($param === 0) {
                $method = $params[0];
                unset($params[0]);
                $orm->$method($params);
                break;
            } else {
                if (is_array($value)) {
                    call_user_func_array(array($orm, $param), $value);
                } else {
                    $orm->$param($value);
                }
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