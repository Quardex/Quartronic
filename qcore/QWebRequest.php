<?php
namespace quarsintex\quartronic\qcore;

class QWebRequest extends QSource
{
    protected $route = '';
    protected $ip;
    protected $host;
    protected $url;
    protected $query;
    protected $userAgent;
    protected $referer;
    protected $time;
    protected $get;
    protected $post;
    protected $request;
    protected $cookie;

    function __construct()
    {
        $this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
        $this->host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false;
        $this->url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : false;
        $this->query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : false;
        $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : false;
        $this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
        $this->time = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : false;
        $this->route = preg_replace('/^[\/]?([^?]*).*/', '$1', $this->url);
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
    }

    function getParam($name, $default)
    {
        return array_key_exists($name, self::$Q->request->request) && self::$Q->request->request[$name] ? self::$Q->request->request[$name] : $default;
    }

}

?>
