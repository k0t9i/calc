<?php

namespace app\models;

class NumToken extends Token
{
    protected function doGetValue(array $args)
    {
        // TODO: Implement doGetValue() method.
    }

    protected function argsCount()
    {
        // TODO: Implement argsCount() method.
    }

    protected function requireValue()
    {
        return true;
    }

    protected function getLexemeRegExp()
    {
        return '[0-9]+(\.?[0-9]+)|([0-9]*)';
    }
}