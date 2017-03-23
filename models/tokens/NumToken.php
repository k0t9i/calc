<?php

namespace app\models\tokens;

/**
 * Number token
 *
 * @package app\models\tokens
 */
class NumToken extends Token
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
    protected function argsCount()
    {
        // TODO: Implement argsCount() method.
    }

    /**
     * @inheritdoc
     */
    protected function requireValue()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function getLexemeRegExp()
    {
        return '[0-9]+(\.?[0-9]+)|([0-9]*)';
    }
}