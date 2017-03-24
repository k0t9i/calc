<?php

namespace app\models\tokens;

/**
 * Multiply token
 *
 * @package app\models\tokens
 */
class MulToken extends OperatorToken
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
        return '\*';
    }

    public function getPrecedence()
    {
        return 2;
    }

    public function getAssociativity()
    {
        return self::ASSOCIATIVE_LEFT;
    }
}