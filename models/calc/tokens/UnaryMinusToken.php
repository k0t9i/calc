<?php

namespace app\models\calc\tokens;

/**
 * Unary minus token
 *
 * @package app\models\calc\tokens
 */
class UnaryMinusToken extends MinusToken
{
    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return -$args[0]->getValue();
    }

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
    public function getPrecedence()
    {
        return self::PRECEDENCE_HIGHEST;
    }

    /**
     * @inheritdoc
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVE_RIGHT;
    }
}
