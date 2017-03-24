<?php

namespace app\models\tokens;

/**
 * Plus token
 *
 * @package app\models\tokens
 */
class PlusToken extends OperatorToken
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
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getLexemeRegExp()
    {
        return '\+';
    }

    public function getPriority()
    {
        return 1;
    }

    public function getAssociativity()
    {
        return self::ASSOCIATIVE_LEFT;
    }
}