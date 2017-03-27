<?php

namespace app\models\calc\tokens;

/**
 * Class FuncToken
 * Base class for all functions
 *
 * @package app\models\calc\tokens
 */
abstract class FuncToken extends OperatorToken
{
    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 1;
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
    public function getPrecedence()
    {
        return self::PRECEDENCE_HIGH;
    }

    /**
     * @inheritdoc
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVE_RIGHT;
    }
}
