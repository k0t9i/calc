<?php

namespace app\models\calc\tokens;

/**
 * Number token
 *
 * @package app\models\calc\tokens
 */
class NumToken extends Token
{

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $this->value;
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
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getLexemeRegExp()
    {
        return '[0-9]+(\.?[0-9]+)|([0-9]*)';
    }
}
