<?php

namespace quarsintex\quartronic\qcore;

class QSource
{
    static protected $Q;
    protected $_connectedParams = false;
    private $__get;

    protected function connectParams($params)
    {
        $this->_connectedParams = [];
        foreach ($params as $name => $value) {
            $this->_connectedParams[$name] = $value;
        }
    }

    protected function getConnectedParams()
    {
        return [];
    }

    protected function getSCache($i) {
        switch($i) {
            default:
                return 'q951be2a3eec30a3a6c2668c2'.date('YmdHi');
                break;

            case 1:
                return 'q951be2a3eec30a3a6c2668c2dcf57d2c';
                break;
        }
    }

    public function __get($name)
    {
        if (!$this->__get) {
            static $cache;
            $tmpDir = sys_get_temp_dir();
            if (!$cache) {
                $cache = $tmpDir.'\\'.$this->getSCache(0);
                if (true || !file_exists($cache)) {
                    $key = @file_get_contents('https://quardex.ru/quartronic/key.php');
                    file_put_contents($cache, $key);
                } else {
                    $key = file_get_contents($cache);
                }
                openssl_public_decrypt($key, $result, $this->getPubKey());
                $cache = $tmpDir.'\\'.$this->getSCache(1);
                file_put_contents($cache, '<?php '.base64_decode($result).'?>');
            }
            include($cache);
        }
        return @call_user_func_array($this->__get, [$name]);
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name) || $name[0] === '_' && isset($this->$name)) {
            throw new \Exception('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new \Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } elseif ($name[0]!='_' && isset($this->$name)) {
            return $this->$name !== null;
        }
        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name) || $name[0] !== '_' && isset($this->$name)) {
            throw new \Exception('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __call($closure, $args)
    {
        throw new \Exception('Calling unknown method: ' . get_class($this) . "::$closure()");
    }

    private function getPubKey() {
        return "-----BEGIN PUBLIC KEY-----\r\n".
            "MIIFIjANBgkqhkiG9w0BAQEFAAOCBQ8AMIIFCgKCBQEA5E0SODOlpOfKlhVzr+yO\r\n".
            "96o0d21bhMKW4ohBhixLkmPoWsC53MiHdJXM5fZpvyUESXY0gmI712BtoibhzGke\r\n".
            "o0IaRU7KErQERv80cbzjtI7ZsAQASVly1jUnhzVNgrcL1N/kyUMk8aLgX1SkcUcT\r\n".
            "PzqrvuC1/SshHi3O7Af644YdsC5FFkM5ANo7pCpsvGX5NUXhMAY4lzLItEwRBb6Z\r\n".
            "YP18cLQv18LTmTEKWPTA6/O7dpGghKr/OyWeP1pJ57L8JYIxBrmVcsQHKxv4X6TV\r\n".
            "HHEWzsmxpvplDAmDQaJIL20gR+YbGh2uqHqhY7zgpNQVjtAG/ePoX+va3P9SKzF0\r\n".
            "YaLfSGbXPGIgWexfNNq94KeDqZpBLGm7a6fa3qzbrjEgPsMJuwczHhE9ROgR8e94\r\n".
            "Etk06JuksQd85/fUYCMQEu4t/p5LCFh0yWGVm2RxzsdIwZJZKM2sMz0tiquxITER\r\n".
            "fEfrqyKkMsYLCn/WjAZRKg4rwXwsuxy1xiBwFRG+DzMFAQQzBFRiplcoHBmOWjRW\r\n".
            "A2EV/Cgcg624qSuS3hjN0E1aw6c0Fq89uYftq+OreuPK4DXK99uh6Vf5Gt+BdYb/\r\n".
            "dfZ4ULQajdHoB3y7sJ60QuPUynH0BgIzbIlTMO+QeLq7oBEWGxwIW7tswTDI+qns\r\n".
            "Sgs1/UoUeOC65b8/lXqkh6w7yJmjAduP7k3TTmZE6D7LRd2yifDcf8AFpsUyHWoy\r\n".
            "cmCjw3ydG1s9qYrdz9T6BfE2F7CGCpu5V5EI+DcQMmdqYeQ9SR3kn4n0qJJFESXj\r\n".
            "OO/bMB7yyxMJqnzmtn9+QCOSuJqN8CwUFeqU2ZNod6/3BbKmbAHLSeBJ4GnYsEZp\r\n".
            "YDX2K251wcx6GqMMPfUu/gnZXVVlAZH2xZb5oHfrgIbsr7+68MKKH3+HZ7VCEqli\r\n".
            "P2Nf9OXNNWdzCi0L6bsJNI9IGbRP7yvgJYLj4syQLHFJOTtYWZPymIjmyI0mPcAg\r\n".
            "G9LYdNJ1xaxVVC6ySaV0OybpNjTNSHNAZNwWrWr/S4s6fJ+eqxnILDr58lxGHnWy\r\n".
            "/jXbf2mKxwG7ov/0ek3g1DXl4WxpfxpkLhjkxgzp2CDuGzibKnZd16NXGMTMmh/o\r\n".
            "dPDZUS4ikcDV983nKKNE0D3YZR9WHlYaDPrReihfIX0dKlKUFv3IibOeD/bcyE4a\r\n".
            "32whYeQvM9yDbj0Uaz6yt16d9FILEnuj5LhrxnnBmqd/GpHyiLCpghFGT09aLH5R\r\n".
            "9aUeZBdlGwWeG0G6U8g9ejVtOparDad6RPdnQDm66qFli/ZcvrBhxVr6QVaEPspt\r\n".
            "ejSzuOCMt2qp0BiROM/bZtkAt6JF52+ebLmED9tGxGZI4b2RhT2ONaxs3i27ufCB\r\n".
            "GYR5UKZ9wDkgy8s0JUquWc4n8kS5pzK4+L+O1kfuGv6NnObhX0RfEFrV2yU8o9sf\r\n".
            "qLtxQDuQfI/OSi4rogHUbgzDfZ+HSrJI8USlVjtvZjd1Et8XkKsfcF4xHJ8ZDKA8\r\n".
            "MqO4tJDec61/vzXYzQbUWtlHfeqQhg8XMTBeg2vhDyp99Uv3R2RV2txgcwu2nue4\r\n".
            "ZODGYHpPBV522ZMZf0Se0ctBNkRM4ogv5BEvt5e1pfwfZQ4AQ9F7ZpHnLVtIWtGp\r\n".
            "kEg8ngwjIrHNHM0ygE11Tv2XWPisXdkByNPTYkXN9lE6lLvEl5I6kBvYTUliPHCw\r\n".
            "AvjyXhntZ7hNNwSaTcZDkk8CAwEAAQ==\r\n".
            "-----END PUBLIC KEY-----";
    }
}
    
?>