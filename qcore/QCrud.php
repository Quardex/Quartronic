<?php
namespace quarsintex\quartronic\qcore;

use quarsintex\quartronic\qcore\QModel;

class QCrud extends QSource
{
    protected $model;
    protected $config;

    public $page = 1;
    public $limit = 10;

    protected function getConnectedProperties()
    {
        return [
            'qRootDir' => &self::$Q->router->qRootDir,
            'configDir' => &self::$Q->router->configDir,
        ];
    }

    public function __construct($modelName)
    {
        $this->config = static::loadConfig();
        $controllerPath = $this->qRootDir . 'qmodels/' . $modelName . '.php';
        if (file_exists($controllerPath)) {
            $modelClass = '\\quarsintex\\quartronic\\qmodels\\'.$modelName;
            $this->model = new $modelClass;
        } else {
            $this->model = new QModel(strtolower($modelName));
        }
        $this->page = intval(self::$Q->request->getParam('page', $this->page));
    }

    static function loadConfig()
    {
        $configPath = self::$Q->router->configDir.'qcrud.php';
        $configFromFile = file_exists($configPath) ? include($configPath) : [];
        $configFromDB = [];
        try {
            foreach ((new QModel('qcrud'))->all as $model) {
                $configFromDB[$model->alias] = json_decode($model->config, true);
            }
        } finally {
            return array_merge($configFromFile, $configFromDB);
        }
    }

    public function getOffset()
    {
        return $this->limit * ($this->page - 1);
    }

    public function getModelFields()
    {
        return $this->model->getFieldList();
    }

    public function getList()
    {
        $model = $this->model;
        if ($this->limit) {
            $model->query->limit($this->limit)->offset($this->offset);
        }
        return $model->all;
    }

    public function create($params)
    {
        $this->model->fields = $params;
        $this->model->save();
    }

    public function read($params)
    {
        if (empty($params['id'])) return null;
        return $this->model->searchByPk($params);
    }

    public function update($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->searchByPk($params);
        $this->model->fields = $params;
        $this->model->save();
    }

    public function delete($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->searchByPk($params);
        if ($this->model) $this->model->delete();
    }

    static function getAutoStructure()
    {
        static $cache;
        if (!$cache) {
            $native = [
                'user' => ['sql' => '
                    CREATE TABLE IF NOT EXISTS `quser` (
                        id INTEGER PRIMARY KEY,
                        username VARCHAR,
                        email VARCHAR,
                        passhash VARCHAR
                    );
                    INSERT OR IGNORE INTO `quser` (id,username,email,passhash) values (1,"Quardex", "megasounds@mail.ru", "$2y$10$4BjY5DHZuqngI3/JlnRH/egyCqiNy88YBx6cjUCnVaWNxhji1dwAG");
                    INSERT OR IGNORE INTO `quser` (id,username,email,passhash) values (2,"Admin", "admin@mail.com", "$2y$10$RneSIIYPJL/J5InEStZx9upSe01XFppg9dqhD19H8N.u0NBfq4Si.");'],
                'group' => ['sql' => '
                    CREATE TABLE IF NOT EXISTS `qgroup` (
                        id integer PRIMARY KEY AUTOINCREMENT,
                        name varchar
                    )'],
                'role' => ['sql' => '
                    CREATE TABLE IF NOT EXISTS `qrole` (
                        id integer PRIMARY KEY AUTOINCREMENT,
                        name varchar
                    )'],
                'section' => ['sql' => '
                    CREATE TABLE IF NOT EXISTS `qsection` (
                        id integer PRIMARY KEY AUTOINCREMENT,
                        name varchar
                    )'],
                'crud' => ['sql' => '
                    CREATE TABLE IF NOT EXISTS `qcrud` (
                        id integer PRIMARY KEY AUTOINCREMENT,
                        alias varchar,
                        config varchar
                    )'],
            ];
            $cache = array_merge($native, self::loadConfig());
        }
        //var_dump($cache);
        return $cache;
    }

    static function restructDB($verbose = false) {
        foreach (self::getAutoStructure() as $name => $struct) {
            if ($verbose) echo "\n".'Preparing table for crud section "'.$name.'"...';
            self::$Q->sysDB->exec($struct['sql']);
            if ($verbose) echo "\nSuccess!\n";
        }
    }
}

?>