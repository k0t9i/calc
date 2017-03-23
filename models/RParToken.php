<?php

namespace app\models;

class RParToken extends Token
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
        return false;
    }

    protected function getLexemeRegExp()
    {
        return '\)';
    }
}