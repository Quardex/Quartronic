<?php
namespace quarsintex\quartronic\qcore;

class QSource
{
    static protected $Q;

    protected $_connectedProperties = [];
    protected $_dynComponents = [];
    private $__get;

    protected function connectProperties($targetNames = null)
    {
        $properties = $this->getConnectedProperties();
        if ($targetNames === null) $targetNames = array_keys($properties);
        foreach ($targetNames as $name) {
            if (isset($properties[$name])) $this->_connectedProperties[$name] = $properties[$name];
        }
    }

    protected function getConnectedProperties()
    {
        return $this->_connectedProperties;
    }

    protected function addConnectedProperty($name, $value)
    {
        $this->_connectedProperties[$name] = $value;
    }

    public function __get($name)
    {
        if (!$this->__get) {
            $this->__get = function($name) {
                $getter = 'get' . $name;
                if (method_exists($this, $getter)) {
                    return $this->$getter();
                } else {
                    if (!array_key_exists($name, $this->_connectedProperties)) $this->connectProperties([$name]);
                    if (array_key_exists($name, $this->_connectedProperties)) {
                            if ($this->_connectedProperties[$name] instanceof QDynUnit) $this->_connectedProperties[$name] = $this->_connectedProperties[$name]->run();
                            return $this->_connectedProperties[$name];
                    } elseif (method_exists($this, 'set' . $name)) {
                        throw new \Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
                    } elseif ($name[0] !== '_' && property_exists($this, $name)) {
                        return $this->$name;
                    }
                }
                throw new \Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
            };
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
        } elseif ($name[0]!='_' && property_exists($this, $name)) {
            return $this->$name !== null;
        }
        return array_key_exists($name, $this->getConnectedProperties());
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

    public function dynUnit($closure)
    {
        return new QDynUnit($closure);
    }
}

?>