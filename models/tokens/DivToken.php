<?php

namespace app\models\tokens;

/**
 * Divide token
 *
 * @package app\models\tokens
 */
class DivToken extends OperatorToken
{

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $args[0]->getValue() / $args[1]->getValue();
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
        return '\/';
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