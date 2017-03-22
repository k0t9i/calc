<?php
/**
 * Created by PhpStorm.
 * User: vershinin
 * Date: 22.03.2017
 * Time: 16:57
 */

namespace app\models;


class NumToken extends Token
{
    protected function doGetValue(array $args)
    {
        return $this->_value;
    }

    protected function argsCount()
    {
        return 0;
    }

    protected function requireValue()
    {
        return true;
    }
}