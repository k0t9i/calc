<?php

namespace app\models;

class PlusToken extends Token
{
    protected function doGetValue(array $args)
    {
        return $args[0] + $args[1];
    }

    protected function argsCount()
    {
        return 2;
    }

    protected function requireValue()
    {
        return false;
    }
}