<?php

namespace app\models\tokens;

/**
 * Minus token
 *
 * @package app\models\tokens
 */
class MinusToken extends OperatorToken
{

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $args[0]->getValue() - $args[1]->getValue();
    }

    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 2;
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
        return '\-';
    }

    public function getPrecedence()
    {
        return 1;
    }

    public function getAssociativity()
    {
        return self::ASSOCIATIVE_LEFT;
    }
}