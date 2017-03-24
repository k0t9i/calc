<?php

namespace app\models\tokens;

/**
 * Left parentheses token
 * 
 * @package app\models\tokens
 */
class LParToken extends Token
{

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        // TODO: Implement doGetValue() method.
    }

    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    protected function requireValue()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getLexemeRegExp()
    {
        return '\(';
    }
}