<?php
namespace quarsintex\quartronic\qcore;

class QCrud extends QSource
{
    protected $model;
    public $page = 1;
    public $limit = 10;

    protected function getConnectedProperties()
    {
        return [
            'qRootDir' => &self::$Q->qRootDir,
        ];
    }

    function __construct($modelName)
    {
        $controllerPath = $this->qRootDir . 'qmodels/' . $modelName . '.php';
        if (file_exists($controllerPath)) {
            $modelClass = '\\quarsintex\\quartronic\\qmodels\\'.$modelName;
            $this->model = new $modelClass;
        } else {
            $this->model = new \quarsintex\quartronic\qcore\QModel(strtolower($modelName));
        }
        $this->page = intval(self::$Q->request->getParam('page', $this->page));
    }

    function getOffset()
    {
        return $this->limit * ($this->page - 1);
    }

    function getModelFields()
    {
        return $this->model->getFieldList();
    }

    function getList()
    {
        $model = $this->model;
        if ($this->limit) {
            $model->query->limit($this->limit)->offset($this->offset);
        }
        return $model->all;
    }

    function create($params)
    {
        $this->model->fields = $params;
        $this->model->save();
    }

    function read($params)
    {
        if (empty($params['id'])) return null;
        return $this->model->search($params);
    }

    function update($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->searchByPk($params);
        $this->model->fields = $params;
        $this->model->save();
    }

    function delete($params)
    {
        if (empty($params['id'])) return null;
        $this->model = $this->model->searchByPk($params);
        if ($this->model) $this->model->delete();
    }

    static function getAutoStructure()
    {
        return [
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
            'news' => ['sql' => '
                CREATE TABLE IF NOT EXISTS `qnews` (
                    id integer PRIMARY KEY AUTOINCREMENT,
                    alias varchar,
                    title varchar,
                    short_text text,
                    text text,
                    created_at timestamp
                )'],
        ];
    }

    static function autostructDB() {
        foreach (self::getAutoStructure() as $name => $struct) {
            echo "\n".'Preparing table for crud section "'.$name.'"...';
            self::$Q->db->exec($struct['sql']);
            echo "\nSuccess!\n";
        }
    }
}

?>