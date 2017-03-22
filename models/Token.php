<?php

namespace app\models;

abstract class Token
{
    protected $_value;

    final public function __construct($value = null)
    {
        if ($this->requireValue() && is_null($value)) {
            throw new \InvalidArgumentException();
        }
        $this->_value = $value;
    }

    public function getValue()
    {
        $args = func_get_args();
        if ($this->argsCount() != count($args)) {
            throw new \InvalidArgumentException();
        }

        return $this->doGetValue($args);
    }

    abstract protected function doGetValue(array $args);
    abstract protected function argsCount();
    abstract protected function requireValue();
}